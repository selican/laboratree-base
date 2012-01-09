<?php
class ProjectsController extends AppController
{
	var $name = 'Projects';

	var $uses = array(
		'Group',
		'Project',
		'ProjectsUsers',
		'Role',
		'Word',
		'Help',
		'Perm',
	);

	var $components = array(
		'Auth',
		'Security',
		'Session',
		'RequestHandler',
		'PermissionCmp',
		'Plugin',
		'Image',
		'FileCmp',
	);

	function beforeFilter()
	{
		$this->Security->validatePost = false;

		parent::beforeFilter();
	}

	/**
	 * Project Dashboard
	 *
	 * @param integer $project_id Project ID
	 */
	function dashboard($project_id = '')
	{
		if(empty($project_id))
		{
			$this->cakeError('missing_field', array('field' => 'Project ID'));
			return;
		}

		if(!is_numeric($project_id) || $project_id < 1)
		{
			$this->cakeError('invalid_field', array('field' => 'Project ID'));
			return;
		}

		$project = $this->Project->find('first', array(
			'conditions' => array(
				'Project.id' => $project_id,
			),
			'recursive' => -1,
		));
		if(empty($project))
		{
			$this->cakeError('invalid_field', array('field' => 'Project ID'));
			return;
		}

		$group = $this->Group->find('first', array(
			'conditions' => array(
				'Group.id' => $project['Project']['group_id'],
			),
			'recursive' => -1,
		));
		if(empty($group))
		{
			$this->cakeError('internal_error', array('action' => 'View', 'resource' => 'Group Dashboard'));
			return;
		}

		$permission = $this->PermissionCmp->check('project.dashboard.view', 'project', $project_id);
		if(!$permission)
		{
			$this->cakeError('access_denied', array('action' => 'View', 'resource' => 'Project'));
			return;
		}

		if($this->RequestHandler->prefers('json'))
		{
			if(!isset($this->params['form']['model']))
			{
				$this->cakeError('missing_field', array('field' => 'Model'));
				return;
			}

			$model = $this->params['form']['model'];
			if(!is_string($model) || empty($model))
			{
				$this->cakeError('invalid_field', array('field' => 'Model'));
				return;
			}

			$plugin = Inflector::camelize($model);

			$builtin = true;
			if(!in_array($model, array('members')))
			{
				$builtin = false;

				if(!in_array($plugin, $this->plugins))
				{
					$this->cakeError('invalid_field', array('field' => 'Model'));
					return;
				}
			}

			$list = array();
			if($builtin)
			{
				switch($model)
				{
					case 'members':
						try {
							$members = $this->ProjectsUsers->users($project_id, 1);
							$list = $this->ProjectsUsers->User->toList('members', $members);
						} catch(Exception $e) {
							$this->cakeError('internal_error', array('action' => 'Retrieve', 'resource' => 'Members'));
							return;
						}
						break;
				}
			}
			else
			{
				if(App::import('Component', $plugin . '.Dashboard'))
				{
					$dashboard = new DashboardComponent();
					$dashboard->initialize($this);

					$list = $dashboard->process('project', $project_id, $this->params);
				}
			}

			$this->set('list', $list);
		}

		$this->pageTitle = 'Project Dashboard';
		$this->set('pageName', $project['Project']['name'] . ' - Dashboard');

		$this->set('project', $project);
		$this->set('project_id', $project_id);

		$this->set('group', $group);
		$this->set('group_id', $group['Group']['id']);

		$context = array(
			'table_type' => 'project',
			'table_id' => $project_id,

			'project_id' => $project_id,
			'group_id' => $group['Group']['id'],

			'permissions' => array(
				'project' => $this->PermissionCmp->feature('project', 'project', $project_id),
			),
		);

		/* Scan Plugins and Check Permissions */
		foreach($this->plugins as $plugin)
		{
			$feature = Inflector::underscore($plugin);
			$context['permissions'][$feature] = $this->PermissionCmp->feature($feature, 'project', $project_id);
		}
		$this->set('context', $context);
	}

	/**
	 * Creates a Project under a Group
	 *
	 * @param integer $group_id Group ID
	 */
	function create($group_id = '')
	{
		$this->pageTitle = 'Create Project';
		$this->set('pageName', 'Create Project');

		if(empty($group_id))
		{
			$this->cakeError('missing_field', array('field' => 'Group ID'));
			return;
		}

		$group = $this->Group->find('first', array(
			'conditions' => array(
				'Group.id' => $group_id,
			),
			'recursive' => -1,
		));
		if(empty($group))
		{
			$this->cakeError('invalid_field', array('Group ID'));
			return;
		}

		$permission = $this->PermissionCmp->check('project.add', 'group', $group_id);
		if(!$permission)
		{
			$this->cakeError('access_denied', array('action' => 'Create', 'resource' => 'Project'));
			return;
		}

		$context = array(
			'group_id' => $group_id,

			'permissions' => array(
				'group' => $permission['mask'],
			),
		);

		$this->set('group', $group);
		$this->set('group_id', $group_id);
		$this->set('context', $context);

		if(!empty($this->data))
		{
			$data = array(
				'Project' => array(
					'name' => $this->data['Project']['name'],
					'group_id' => $group_id,
				),	
				'ProjectsUsers' => array(
					array(
						'user_id' => $this->Session->read('Auth.User.id'),
					),
				),
				'Role' => array(
					array(
						'table_type' => 'project',
						'name' => 'Administrator',
						'read_only' => 1,
					),
					array(
						'table_type' => 'project',
						'name' => 'Manager',
						'read_only' => 1,
					),
					array(
						'table_type' => 'project',
						'name' => 'Member',
						'read_only' => 1,
					),
				),
			);
			$this->Project->bindModel(
			array(
				'hasMany' => array(
					'ProjectsUsers',
				),
			)
			, false);

			if(!$this->Project->saveAll($data))
			{
				$this->cakeError('internal_error', array('action' => 'Create', 'resource' => 'Project'));
				return;
			}

			$project_id = $this->Project->id;

			$relationship = $this->ProjectsUsers->field('id', array(
				'user_id' => $this->Session->read('Auth.User.id'),
				'project_id' => $project_id,
			));
			if(empty($relationship))
			{
				$this->cakeError('internal_error', array('action' => 'Create', 'resource' => 'Project'));
				return;
			}

			$role = $this->Role->field('id', array(
				'table_type' => 'project',
				'table_id' => $project_id,
				'name' => 'Administrator',
			));
			if(empty($role))
			{
				$this->cakeError('internal_error', array('action' => 'Create', 'resource' => 'Project'));
				return;
			}

			$this->ProjectsUsers->id = $relationship;
			if(!$this->ProjectsUsers->saveField('role_id', $role))
			{
				$this->cakeError('internal_error', array('action' => 'Create', 'resource' => 'Project'));
				return;
			}

			try {
				$this->PermissionCmp->setup('project', $project_id);
			} catch(Exception $e) {
				$this->cakeError('internal_error', array('action' => 'Create', 'resource' => 'Project'));
				return;
			}

			try {
				$this->Plugin->broadcastListeners('project.add', array(
					$project_id,
					$this->data['Project']['name'],
				));
				$this->Plugin->broadcastListeners('project.adduser', array(
					$project_id,
					$this->Session->read('Auth.User.id'),
					$this->Session->read('Auth.User.username'),
					$this->Session->read('Auth.User.name'),
				));
			} catch(Exception $e) {
				$this->cakeError('internal_error', array('action' => 'Create', 'resource' => 'Project'));
				return;
			}

			$this->redirect('/projects/adduser/' . $project_id);
			return;
		}
	}

	/**
	 * Edits a Project
	 *
	 * @param integer $project_id Project Id
	 */
	function edit($project_id = '')
	{
		if(empty($project_id))
		{
			$this->cakeError('missing_field', array('field' => 'Project ID'));
			return;
		}

		if(!is_numeric($project_id) || $project_id < 1)
		{
			$this->cakeError('invalid_field', array('field' => 'Project ID'));
			return;
		}

		$project = $this->Project->find('first', array(
			'conditions' => array(
				'Project.id' => $project_id,
			),
			'recursive' => -1,
		));
		if(empty($project))
		{
			$this->cakeError('invalid_field', array('action' => 'Edit', 'resource' => 'Project'));
			return;
		}

		$group = $this->Group->find('first', array(
			'conditions' => array(
				'Group.id' => $project['Project']['group_id'],
			),
			'recursive' => -1,
		));
		if(empty($group))
		{
			$this->cakeError('internal_error', array('action' => 'Edit', 'resource' => 'Project'));
			return;
		}

		$permission = $this->PermissionCmp->check('project.edit', 'project', $project_id);
		if(!$permission)
		{
			$this->cakeError('access_denied', array('action' => 'Edit', 'resource' => 'Project'));
			return;
		}

		$this->pageTitle = 'Edit Project - ' . $project['Project']['name'];
		$this->set('pageName', $project['Project']['name'] . ' - Edit Project');

		$this->set('project', $project);
		$this->set('project_id', $project_id);

		$this->set('group', $group);
		$this->set('group_id', $group['Group']['id']);

		/* Create Context */
		$context = array(
			'table_type' => 'project',
			'table_id' => $project_id,

			'project_id' => $project_id,
			'group_id' => $group['Group']['id'],

			'permissions' => array(
				'project' => $permission['mask'],
			),
		);
		$this->set('context', $context);

		if(!empty($this->data))
		{
			$this->data['Project']['id'] = $project_id;

			if($this->FileCmp->is_uploaded_file($this->data['Project']['picture']['tmp_name']))
			{	
				$mimetype = $this->FileCmp->mimetype($this->data['Project']['picture']['tmp_name']);

				$extension = '';
				switch($mimetype)
				{
					case 'image/gif':
						$extension = 'gif';
						break;
					case 'image/jpeg':
						$extension = 'jpg';
						break;
					case 'image/png':
						$extension = 'png';
						break;
					case 'image/gif; charset=binary':
						$extension = 'gif';
						break;
					case 'image/jpeg; charset=binary':
						$extension = 'jpg';
						break;
					case 'image/png; charset=binary':
						$extension = 'png';
						break;

					default:
						$this->Session->setFlash('The image format you specified was invalid', 'default', array(), 'status');
						$this->redirect('/projects/edit/' . $project_id);
						return;
				}

				/* Generate New Image Filename to Avoid Caching Problems */
				$filename = md5(uniqid('', true));

				/* Resize Image to 200 width */
				$destination = IMAGES . 'projects/' . $filename . '.png';
				//TODO: Test return value of scale
				$image = $this->Image->scale($this->data['Project']['picture']['tmp_name'], 'auto', 200);
				$fp = fopen($destination, 'wb');
				fwrite($fp, $image);
				fclose($fp);

				/* Resize Image to 50 width */
				$destination = IMAGES . 'projects/' . $filename . '_thumb.png';
				$image = $this->Image->crop($this->data['Project']['picture']['tmp_name'], 50, 50);
				$fp = fopen($destination, 'wb');
				fwrite($fp, $image);
				fclose($fp);

				/* Remove Old Picture */
				if(!empty($project['Project']['picture']))
				{
					$destination = IMAGES . "projects/{$project['Project']['picture']}.png";
					if(file_exists($destination))
					{
						unlink($destination);
					}

					$destination = IMAGES . "projects/{$project['Project']['picture']}_thumb.png";
					if(file_exists($destination))
					{
						unlink($destination);
					}
				}

				$this->data['Project']['picture'] = $filename;
			}
			else
			{
				unset($this->data['Project']['picture']);
			}

			if(!$this->Project->save($this->data))
			{
				$this->cakeError('internal_error', array('action' => 'Edit', 'resource' => 'Project'));
			}

			try {
				$this->Plugin->broadcastListeners('project.edit', array(
					$project_id,
					$project['Project']['name'],
					$this->data['Project']['name'],
				));
			} catch(Exception $e) {
				$this->cakeError('internal_error', array('action' => 'Edit', 'resource' => 'Project'));
				return;
			}

			$this->Session->setFlash('Project Information Updated', 'default', array(), 'status');
			$this->redirect('/projects/edit/' . $project_id);
			return;
		}

		if($this->RequestHandler->prefers('json'))
		{
			$node = $this->Project->toNode($project);

			$response = array(
				'success' => true,
				'project' => $node,
			);
			$this->set('response', $response);
		}
	}

	/**
	 * Deletes a Project
	 *
	 * @param integer $project_id Project ID
	 */
	function delete($project_id = '')
	{
		if(!$this->RequestHandler->prefers('json'))
		{
			$this->cakeError('error404');
			return;
		}

		if(empty($project_id))
		{
			$this->cakeError('missing_field', array('field' => 'Project ID'));
			return;
		}

		if(!is_numeric($project_id) || $project_id < 1)
		{
			$this->cakeError('invalid_field', array('field' => 'Project ID'));
			return;
		}

		$this->Project->id = $project_id;
		if(!($this->Project->exists()))
		{
			$this->cakeError('invalid_field', array('field' => 'Project ID'));
			return;
		}
		$name = $this->Project->field('name');

		$permission = $this->PermissionCmp->check('project.delete', 'project', $project_id);
		if(!$permission)
		{
			$this->cakeError('access_denied', array('action' => 'Delete', 'resource' => 'Project'));
			return;
		}

		$this->Project->delete($project_id, true);

		try {
			$this->Plugin->broadcastListeners('project.delete', array(
				$project_id,
				$name,
			));
		} catch(Exception $e) {
			$this->cakeError('internal_error', array('action' => 'Delete', 'resource' => 'Project'));
			return;
		}

		$response = array(
			'success' => true,
		);

		$this->set('response', $response);
	}

	/**
	 * Removes a User from a Project
	 *
	 * @param integer $project_id Project ID
	 * @param integer $user_id    User ID
	 */
	function removeuser($project_id = '', $user_id = '')
	{
		if(!$this->RequestHandler->prefers('json'))
		{
			$this->cakeError('error404');
			return;
		}

		if(empty($project_id))
		{
			$this->cakeError('missing_field', array('field' => 'Project ID'));
			return;
		}

		if(!is_numeric($project_id) || $project_id < 1)
		{
			$this->cakeError('invalid_field', array('field' => 'Project ID'));
			return;
		}

		if(empty($user_id))
		{
			$this->cakeError('missing_field', array('field' => 'User ID'));
			return;
		}

		if(!is_numeric($user_id) || $user_id < 1)
		{
			$this->cakeError('invalid_field', array('field' => 'User ID'));
			return;
		}

		if($user_id == $this->Session->read('Auth.User.id'))
		{
			$this->cakeError('invalid_field', array('field' => 'User ID'));
			return;
		}

		$project = $this->Project->find('first', array('conditions' => array('Project.id' => $project_id), 'recursive' => -1));
		if(empty($project))
		{
			$this->cakeError('invalid_field', array('field' => 'Project ID'));
			return;
		}

		$user = $this->User->find('first', array('conditions' => array('User.id' => $user_id), 'recursive' => -1));
		if(empty($user))
		{
			$this->cakeError('invalid_field', array('field' => 'User ID'));
			return;
		}

		$permission = $this->PermissionCmp->check('project.members.delete', 'project', $project_id);
		if(!$permission)
		{
			$this->cakeError('access_denied', array('action' => 'Remove', 'resource' => 'Project Member'));
			return;
		}

		$conditions = array(
			'ProjectsUsers.project_id' => $project_id,
			'ProjectsUsers.user_id' => $user_id,
		);
		$relationship = $this->ProjectsUsers->find('first', array('conditions' => $conditions, 'recursive' => -1));
		if(empty($relationship))
		{
			$this->cakeError('invalid_field', array('field' => 'User ID'));
			return;
		}

		$this->ProjectsUsers->delete($relationship['ProjectsUsers']['id']);

		try {
			$this->Plugin->broadcastListeners('project.removeuser', array(
				$project_id,
				$user_id,
				$user['User']['username'],
				$project['Project']['name'],
			));
		} catch(Exception $e) {
			$this->cakeError('internal_error', array('action' => 'Remove', 'resource' => 'Project Member'));
			return;
		}

		$this->set('response', array(
			'success' => true,
		));
	}

	/**
	 * Adds a User to a Project
	 *
	 * @param integer $project_id Project ID
	 */
	function adduser($project_id = '')
	{
		if(empty($project_id))
		{
			$this->cakeError('missing_field', array('field' => 'Project ID'));
			return;
		}

		if(!is_numeric($project_id) || $project_id < 1)
		{
			$this->cakeError('invalid_field', array('field' => 'Project ID'));
			return;
		}

		$conditions = array(
			'Project.id' => $project_id,
		);

		$project = $this->Project->find('first', array('conditions' => $conditions, 'recursive' => 1));
		if(empty($project))
		{
			$this->cakeError('invalid_field', array('field' => 'Project ID'));
			return;
		}

		$group = $this->Group->find('first', array(
			'conditions' => array(
				'Group.id' => $project['Project']['group_id'],
			),
			'recursive' => -1,
		));
		if(empty($group))
		{
			$this->cakeError('internal_error', array('action' => 'Edit', 'resource' => 'Project'));
			return;
		}

		$permission = $this->PermissionCmp->check('project.members.add', 'project', $project_id);
		if(!$permission)
		{
			$this->cakeError('access_denied', array('action' => 'Add', 'resource' => 'Project Member'));
			return;
		}

		$this->pageTitle = 'Add Project Members - ' .  $project['Project']['name'];
		$this->set('pageName', $project['Project']['name'] . ' - Add Project Members');

		$this->set('project', $project);
		$this->set('project_id', $project_id);

		$this->set('group', $group);
		$this->set('group_id', $group['Group']['id']);

		$context = array(
			'table_type' => 'project',
			'table_id' => $project_id,

			'project_id' => $project_id,
			'group_id' => $group['Group']['id'],

			'permissions' => array(
				'project' => $permission['mask'],
			),
		);
		$this->set('context', $context);

		if($this->RequestHandler->prefers('json'))
		{
			$response = array(
				'success' => true,
			);

			$usersToAdd = array();

			/* Process the selections from the colleagues, and search tabs */
			if(isset($this->params['form']['add_users']) && !empty($this->params['form']['add_users']))
			{
				$users = explode(',', $this->params['form']['add_users']);
				foreach($users as $token)
				{
					if(empty($token))
					{
						continue;
					}

					if(!preg_match('/^(user|project|project|email):(\S+)$/', $token, $matches))
					{
						continue;
					}

					list(,$type, $user_id) = $matches;

					/* Add the selected colleagues to the list of recipients */
					$usersToAdd[] = array(
						'user_type' => $type,
						'user_id' => $user_id,
					);
				}

				/* Add users to the project. */
				if(!empty($usersToAdd))
				{
					$memberRoleId = $this->Role->field('id', array(
						'table_type' => 'project',
						'table_id' => $project_id,
						'name' => 'Member',
					));
					if(empty($memberRoleId))
					{
						$this->cakeError('internal_error', array('action' => 'Add', 'resource' => 'Project Member Role'));
						return;
					}

					foreach($usersToAdd as $user)
					{
						$data = array(
							'ProjectsUsers' => array(
								'project_id' => $project_id,
								'user_id' => $user['user_id'],
								'role_id' => $memberRoleId,
							),
						);

						$this->ProjectsUsers->create();

						if(!$this->ProjectsUsers->save($data))
						{
							// Error on failed add.
							$this->cakeError('internal_error', array('action' => 'Add', 'resource' => 'Project Members'));
							$response['success'] = false;
							$this->set('response', $response);
							return;
						}
					}
				}

				try
				{
					$this->Plugin->broadcastListeners('project.adduser', array(
						$project_id,
						$this->Session->read('Auth.User.id'),
						$this->Session->read('Auth.User.username'),
						$this->Session->read('Auth.User.name'),
					));
				}
				catch(Exception $e)
				{
					$this->cakeError('internal_error', array('action' => 'Add User', 'resource' => 'Project'));
					return;
				}

				$this->set('response', $response);
				$this->render();
				return;
			}

			$action = 'colleagues';
			if(isset($this->params['form']['action']))
			{
				$action = $this->params['form']['action'];
			}

			switch($action)
			{
				case 'colleagues':
					// Contains Project and Project members
					try {
						$colleagues = $this->User->colleagues($this->Session->read('Auth.User.id'));
					} catch(Exception $e) {
						$this->cakeError('internal_error', array('action' => 'Retrieve', 'resource' => 'Colleagues'));
						return;
					}

					// Find existing members and remove them
					$conditions = array(
						'ProjectsUsers.project_id' => $project_id,
					);
					$fields = array(
						'ProjectsUsers.user_id',
						'ProjectsUsers.role_id',
					);
					$users = $this->ProjectsUsers->find('list', array('conditions' => $conditions, 'fields' => $fields));

					$count = count($colleagues);
					for($i = 0; $i < $count; $i++)
					{
						if(isset($users[$colleagues[$i]['User']['id']]))
						{
							unset($colleagues[$i]);
						}
					}

					try {
						$nodes = $this->User->toNodes($colleagues);
					} catch(Exception $e) {
						$this->cakeError('internal_error', array('action' => 'Convert', 'resource' => 'Colleagues'));
						return;
					}

					$response = array(
						'success' => true,
						'colleagues' => $nodes,
					);
					break;
				case 'search':
					$query = null;
					if(isset($this->params['form']['query']))
					{
						$query = trim($this->params['form']['query']);
					}

					$searchResults = $this->User->find('all', array(
						'conditions' => array(
							'User.name LIKE' => '%' . $query . '%',
						),
						'recursive' => -1
					));

					// Determine if members in the search results are already in the project.
					$existingUsers = $this->ProjectsUsers->find('list', array(
						'conditions' => array(
							'ProjectsUsers.project_id' => $project_id,
						),
						'fields' => array(
							'ProjectsUsers.user_id',
						),
					));

					// Remove pre-existing members from the search results.
					$count = count($searchResults);
					for($i = 0; $i < $count; $i++)
					{
						if(in_array($searchResults[$i]['User']['id'], $existingUsers))
						{
							unset($searchResults[$i]);
						}
					}

					$matches = array();
					foreach($searchResults as $user)
					{
						$node = array(
							'token' => 'user:' . $user['User']['id'],
							'name' => $user['User']['name'],
						);

						$matches[] = $node;
					}

					$response = array(
						'success' => true,
						'results' => $matches,
					);
					break;
				default:
					$response = array(
						'success' => false,
					);
			}

			$this->set('response', $response);
		}
	}

	/**
	 * Leaves a Project
	 *
	 * @param integer $project_id Project ID
	 */
	function leave($project_id = '')
	{
		if(empty($project_id))
		{
			$this->cakeError('missing_field', array('field' => 'Project ID'));
			return;
		}

		if(!is_numeric($project_id) || $project_id < 1)
		{
			$this->cakeError('invalid_field', array('field' => 'Project ID'));
			return;
		}

		$project = $this->Project->find('first', array(
			'conditions' => array(
				'Project.id' => $project_id,
			),
			'recursive' => -1,
		));
		if(empty($project))
		{
			$this->cakeError('invalid_field', array('field' => 'Project ID'));
			return;
		}

		$group = $this->Group->find('first', array(
			'conditions' => array(
				'Group.id' => $project['Project']['group_id'],
			),
			'recursive' => -1,
		));
		if(empty($group))
		{
			$this->cakeError('internal_error', array('action' => 'Leave', 'resource' => 'Project'));
			return;
		}

		$role = $this->get_project_role($project_id);
		if($role === false)
		{
			$this->cakeError('invalid_field', array('field' => 'Project ID'));
			return;
		}

		if($role == 'Administrator')
		{
			// TODO: Customize error message
			$this->cakeError('invalid_field', array('field' => 'Project ID'));
			return;
		}

		$relationship = $this->ProjectsUsers->find('first', array(
			'conditions' => array(
				'ProjectsUsers.project_id' => $project_id,
				'ProjectsUsers.user_id' => $this->Session->read('Auth.User.id'),
			),
			'recursive' => -1,
		));
		if(empty($relationship))
		{
			$this->cakeError('invalid_field', array('field' => 'Project ID'));
			return;
		}

		$this->ProjectsUsers->delete($relationship['ProjectsUsers']['id']);

		try
		{
			$this->Plugin->broadcastListeners('project.removeuser', array(
				$project_id,
				$this->Session->read('Auth.User.id'),
				$this->Session->read('Auth.User.username'),
				$project['Project']['name'],
			));
		}
		catch(Exception $e)
		{
			$this->cakeError('internal_error', array('action' => 'Leave', 'resource' => 'Project'));
			return;
		}

		$this->redirect('/projects/group/' . $group['Group']['id']);
		return;
	}

	/**
	 * Lists Group Projects
	 *
	 * @param integer $group_id Group ID
	 */
	function group($group_id = '')
	{
		if(empty($group_id))
		{
			$this->cakeError('missing_field', array('field' => 'Group ID'));
			return;
		}

		if(!is_numeric($group_id) || $group_id < 1)
		{
			$this->cakeError('invalid_field', array('field' => 'Group ID'));
			return;
		}

		$group = $this->Group->find('first', array('conditions' => array('Group.id' => $group_id), 'recursive' => -1));
		if(empty($group))
		{
			$this->cakeError('invalid_field', array('field' => 'Group ID'));
			return;
		}

		$permission = $this->PermissionCmp->check('group.projects.view', 'group', $group_id);
		if(!$permission)
		{
			$this->cakeError('access_denied', array('action' => 'View', 'resource' => 'Projects'));
			return;
		}

		$limit = 30;
		if(isset($this->params['form']['limit']))
		{
			$limit = $this->params['form']['limit'];
		}

		if(!is_numeric($limit) || $limit < 1)
		{
			$this->cakeError('invalid_field', array('field' => 'Limit'));
			return;
		}

		$start = 0;
		if(isset($this->params['form']['start']))
		{
			$start = $this->params['form']['start'];
		}

		if(!is_numeric($start) || $start < 0)
		{
			$this->cakeError('invalid_field', array('field' => 'Limit'));
			return;
		}

		$this->pageTitle = 'Projects - ' . $group['Group']['name'];
		$this->set('pageName', $group['Group']['name'] . ' - Projects');	

		$this->set('group', $group);
		$this->set('group_id', $group_id);

		$context = array(
			'table_type' => 'group',
			'table_id' => $group_id,

			'group_id' => $group_id,

			'permissions' => array(
				'group' => $permission['mask'],
			),
		);
		$this->set('context', $context);

		if($this->RequestHandler->prefers('json'))
		{
			$this->Project->bindModel(array(
				'hasOne' => array(
					'CurrentUser' => array(
						'className' => 'ProjectsUsers',
						'foreignKey' => 'project_id',
						'conditions' => array(
							'CurrentUser.user_id' => $this->Session->read('Auth.User.id'),
						),
					),
				),
			), false);

			$projects = $this->Project->find('all', array(
				'conditions' => array(
					'Project.group_id' => $group_id,
				),
				'contain' => array(
					'CurrentUser.Role.Perm',
				),
				'limit' => $limit,
				'offset' => $start,
			));
			try {
				$nodes = $this->Project->toList('projects', $projects);
			} catch(Exception $e) {
				$this->cakeError('internal_error', array('action' => 'Convert', 'resource' => 'Projects'));
				return;
			}

			$this->set('nodes', $nodes);
		}
	}

	/**
	 * Lists Project Members
	 *
	 * @param integer $project_id Project ID
	 */
	function members($project_id)
	{
		if(empty($project_id))
		{
			$this->cakeError('missing_field', array('field' => 'Project ID'));
			return;
		}

		if(!is_numeric($project_id) || $project_id < 1)
		{
			$this->cakeError('invalid_field', array('field' => 'Project ID'));
			return;
		}

		$project = $this->Project->find('first', array(
			'conditions' => array(
				'Project.id' => $project_id,
			),
			'recursive' => 1,
		));
		if(empty($project))
		{
			$this->cakeError('invalid_field', array('field' => 'Project ID'));
			return;
		}

		$group = $this->Group->find('first', array(
			'conditions' => array(
				'Group.id' => $project['Project']['group_id'],
			),
			'recursive' => -1,
		));
		if(empty($group))
		{
			$this->cakeError('internal_error', array('action' => 'View', 'resource' => 'Group Dashboard'));
			return;
		}

		$permission = $this->PermissionCmp->check('project.members.view', 'project', $project_id);
		if(!$permission)
		{
			$this->cakeError('access_denied', array('action' => 'Manage', 'resource' => 'Project Members'));
			return;
		}

		$this->pageTitle = 'Project Members - ' . $project['Project']['name'];
		$this->set('pageName', $project['Project']['name'] . ' - Project Members');

		$this->set('project', $project);
		$this->set('project_id', $project_id);

		$this->set('group', $group);
		$this->set('group_id', $group['Group']['id']);

		$context = array(
			'table_type' => 'project',
			'table_id' => $project_id,

			'project_id' => $project_id,
			'group_id' => $group['Group']['id'],

			'permissions' => array(
				'project' => $permission['mask'],
			),
		);
		$this->set('context', $context);

		if($this->RequestHandler->prefers('json'))
		{
			$action = 'members';
			if(isset($this->params['form']['action']))
			{
				$action = $this->params['form']['action'];
			}

			$response = array(
				'success' => false,
			);

			switch($action) {
				case 'members':
					$xaction = 'read';
					if(isset($this->params['form']['xaction']))
					{
						$xaction = $this->params['form']['xaction'];
					}

					switch($xaction)
					{
						case 'update':
							if(!isset($this->params['form']['members']))
							{
								$this->cakeError('missing_field', array('field' => 'Members'));
								return;
							}

							$members = (array) json_decode($this->params['form']['members']);
							if(empty($members))
							{
								$this->cakeError('invalid_field', array('field' => 'Members'));
								return;
							}

							$user = $this->User->find('first', array(
								'conditions' => array(
									'User.id' => $members['id'],
								),
								'recursive' => -1,
							));
							if(empty($user))
							{
								$this->cakeError('invalid_field', array('field' => 'User'));
								return;
							}

							$relationship = $this->ProjectsUsers->find('first', array(
								'conditions' => array(
									'ProjectsUsers.project_id' => $project_id,
									'ProjectsUsers.user_id' => $members['id'],
								),
								'recursive' => -1,
							));
							if(empty($relationship))
							{
								$this->cakeError('invalid_field', array('field' => 'User'));
								return;
							}

							$role = $this->Role->find('first', array(
								'conditions' => array(
									'Role.table_type' => 'project',
									'Role.table_id' => $project_id,
									'Role.id' => $members['role'],
								),
							));
							if(empty($role))
							{
								$this->cakeError('invalid_field', array('field' => 'Role'));
								return;
							}

							$response['success'] = true;

							$this->ProjectsUsers->id = $relationship['ProjectsUsers']['id'];
							if(!$this->ProjectsUsers->saveField('role_id', $members['role']))
							{
								$response['success'] = false;
							}
							break;
						case 'read':
							try {
								$members = $this->ProjectsUsers->users($project_id);
								$response = $this->User->toList('members', $members);
							} catch(Exception $e) {
								$this->cakeError('internal_error', array('action' => 'Retrieve/Convert', 'resource' => 'Project Members'));
								return;
							}
							break;
					}
					break;
				case 'roles':
					$roles = $this->Role->find('all', array(
						'conditions' => array(
							'Role.table_type' => 'project',
							'Role.table_id' => $project_id,
						),
						'order' => 'Role.id ASC',
						'recursive' => -1,
					));
					try {
						$response = $this->Role->toList('roles', $roles);
					} catch(Exception $e) {
						$this->cakeError('internal_error', array('action' => 'Convert', 'resource' => 'Roles'));
						return;
					}
					break;
			}

			$this->set('response', $response);
		}
	}

	/**
	 * Help for Dashboard
	 */
	function help_dashboard()
	{
		$this->pageTitle = 'Help - Dashboard - Projects';
		$this->set('pageName', 'Projects - Dashboard - Help');
	}

	/**
	 * Help for Create
	 */
	function help_create()
	{
		$this->pageTitle = 'Help - Create - Projects';
		$this->set('pageName', 'Projects - Create - Help');
	}

	/**
	 * Help for Edit
	 */
	function help_edit()
	{
		$this->pageTitle = 'Help - Edit - Projects';
		$this->set('pageName', 'Projects - Edit - Help');
	}

	/**
	 * Help for Add User
	 */
	function help_adduser()
	{
		$this->pageTitle = 'Help - Add User - Projects';
		$this->set('pageName', 'Projects - Add User - Help');
	}

	/**
	 * Help for Group
	 */
	function help_group()
	{
		$this->pageTitle = 'Help - Group - Projects';
		$this->set('pageName', 'Projects - Group - Help');
	}

	/**
	 * Help for Members
	 */
	function help_members()
	{
		$this->pageTitle = 'Help - Members - Projects';
		$this->set('pageName', 'Projects - Members - Help');
	}
}
?>
