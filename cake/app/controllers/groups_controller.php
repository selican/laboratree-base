<?php
class GroupsController extends AppController
{
	var $name = 'Groups';

	var $uses = array(
		'Group',
		'Project',
		'GroupsUsers',
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
		'FileCmp',
		'Plugin',
	);

	function beforeFilter()
	{
		$this->Security->validatePost = false;
		$this->Auth->allow('test');

		parent::beforeFilter();
	}

	function index()
	{
		$this->redirect('/groups/user');
		return;
	}

	/**
	 * Group Dashboard
	 *
	 * @param integer $group_id Group ID
	 */
	function dashboard($group_id = '')
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

		$group = $this->Group->find('first', array(
			'conditions' => array(
				'Group.id' => $group_id,
			),
			'recursive' => -1,
		));
		if(empty($group))
		{
			$this->cakeError('invalid_field', array('field' => 'Group ID'));
			return;
		}

		$permission = $this->PermissionCmp->check('group.dashboard.view', 'group', $group_id);
		if(!$permission)
		{
			$this->cakeError('access_denied', array('action' => 'View', 'resource' => 'Group Dashboard'));
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
			if(empty($model) || !is_string($model))
			{
				$this->cakeError('invalid_field', array('field' => 'Model'));
				return;
			}

			$plugin = Inflector::camelize($model);

			$builtin = true;
			if(!in_array($model, array('projects', 'members')))
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
					case 'projects':
						$actual = array();

						$projects = $this->Project->find('all', array(
							'conditions' => array(
								'Project.group_id' => $group_id,
							),
							'contain' => array(
								'User',
							),
							'order' => 'Project.name',
						));

						try {
							$list = $this->Project->toList('projects', $projects);
						} catch(Exception $e) {
							$this->cakeError('internal_error', array('action' => 'Convert', 'resource' => 'Projects'));
							return;
						}
						break;
					case 'members':
						try {
							$members = $this->GroupsUsers->users($group_id, 1);
							$list = $this->GroupsUsers->User->toList('members', $members);
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

					$list = $dashboard->process('group', $group_id, $this->params);
				}
			}

			$this->set('list', $list);
		}

		$this->pageTitle = 'Group Dashboard - ' . $group['Group']['name'];
		$this->set('pageName', $group['Group']['name'] . ' - Group Dashboard');

		$this->set('group', $group);
		$this->set('group_id', $group_id);

		$this->set('permission', $permission);

		$context = array(
			'table_type' => 'group',
			'table_id' => $group_id,

			'group_id' => $group_id,

			'permissions' => array(
				'group' => $this->PermissionCmp->feature('group', 'group', $group_id),
				'project' => $this->PermissionCmp->feature('project', 'group', $group_id),
			),
		);

		/* Scan Plugins and Check Permissions */
		foreach($this->plugins as $plugin)
		{
			$feature = Inflector::underscore($plugin);
			$context['permissions'][$feature] = $this->PermissionCmp->feature($feature, 'group', $group_id);
		}

		$this->set('context', $context);
	}

	/**
	 * Creates a Group
	 */
	function create()
	{
		$this->pageTitle = 'Create Group';
		$this->set('pageName', 'Create Group');

		if(!empty($this->data))
		{
			$data = array(
				'Group' => array(
					'name' => $this->data['Group']['name'],
				),	
				'GroupsUsers' => array(
					array(
						'user_id' => $this->Session->read('Auth.User.id'),
					),
				),
				'Role' => array(
					array(
						'table_type' => 'group',
						'name' => 'Administrator',
						'read_only' => 1,
					),
					array(
						'table_type' => 'group',
						'name' => 'Manager',
						'read_only' => 1,
					),
					array(
						'table_type' => 'group',
						'name' => 'Member',
						'read_only' => 1,
					),
				),
			);

			$this->Group->bindModel(array(
				'hasMany' => array(
					'GroupsUsers',
				),
			), false);

			if(!$this->Group->saveAll($data))
			{	
				$this->cakeError('internal_error', array('action' => 'Create', 'resource' => 'Group'));
				return;
			}
			$group_id = $this->Group->id;

			$relationship = $this->GroupsUsers->field('id', array(
				'user_id' => $this->Session->read('Auth.User.id'),
				'group_id' => $group_id,
			));
			if(empty($relationship))
			{
				$this->cakeError('internal_error', array('action' => 'Create', 'resource' => 'Group'));
				return;
			}

			$admin = $this->Role->field('id', array(
				'table_type' => 'group',
				'table_id' => $group_id,
				'name' => 'Administrator',
			));
			if(empty($admin))
			{
				$this->cakeError('internal_error', array('action' => 'Create', 'resource' => 'Group'));
				return;
			}

			$this->GroupsUsers->id = $relationship;
			if(!$this->GroupsUsers->saveField('role_id', $admin))
			{
				$this->cakeError('internal_error', array('action' => 'Create', 'resource' => 'Group'));
				return;
			}

			try {
				$this->PermissionCmp->setup('group', $group_id);
			} catch(Exception $e) {
				$this->cakeError('internal_error', array('action' => 'Create', 'resource' => 'Group'));
				return;
			}

			try {
				$this->Plugin->broadcastListeners('group.add', array(
					$group_id,
					$this->data['Group']['name'],
				));
			} catch(Exception $e) {
				$this->cakeError('internal_error', array('action' => 'Create', 'resource' => 'Group'));
				return;
			}

			try {
				$this->Plugin->broadcastListeners('group.adduser', array(
					$group_id,
					$this->Session->read('Auth.User.id'),
					$this->Session->read('Auth.User.username'),
					$this->Session->read('Auth.User.name'),
				));
			} catch(Exception $e) {
				$this->cakeError('internal_error', array('action' => 'Create', 'resource' => 'Group'));
				return;
			}

			$this->redirect('/groups/adduser/' . $group_id);
			return;
		}
	}

	/**
	 * Edits a Group
	 *
	 * @param integer $group_id Group ID
	 */
	function edit($group_id = '')
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

		$group = $this->Group->find('first', array(
			'conditions' => array(
				'Group.id' => $group_id,
			),
			'fields' => array(
				'id',
				'name',
			),
			'recursive' => -1,
		));
		if(empty($group))
		{
			$this->cakeError('invalid_field', array('action' => 'Edit', 'resource' => 'Group'));
			return;
		}

		$permission = $this->PermissionCmp->check('group.edit', 'group', $group_id);
		if(!$permission)
		{
			$this->cakeError('access_denied', array('action' => 'Edit', 'resource' => 'Group Information'));
			return;
		}

		$this->pageTitle = 'Edit Group ' .  $group['Group']['name'];
		$this->set('pageName', $group['Group']['name'] . ' - Edit Group');

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

		if(!empty($this->data))
		{
			$this->data['Group']['id'] = $group_id;

			if($this->FileCmp->is_uploaded_file($this->data['Group']['picture']['tmp_name']))
			{	
				$mimetype = $this->FileCmp->mimetype($this->data['Group']['picture']['tmp_name']);

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
						$this->redirect('/groups/edit/' . $group_id);
						return;
				}

				/* Generate New Image Filename to Avoid Caching Problems */
				$filename = md5(uniqid('', true));

				/* Resize Image to 200 width */
				$destination = IMAGES . 'groups/' . $filename . '.png';
				//TODO: Test return value of scale
				$image = $this->Image->scale($this->data['Group']['picture']['tmp_name'], 'auto', 200);
				$fp = fopen($destination, 'wb');
				fwrite($fp, $image);
				fclose($fp);

				/* Resize Image to 50 width */
				$destination = IMAGES . 'groups/' . $filename . '_thumb.png';
				$image = $this->Image->crop($this->data['Group']['picture']['tmp_name'], 50, 50);
				$fp = fopen($destination, 'wb');
				fwrite($fp, $image);
				fclose($fp);

				/* Remove Old Picture */
				if(!empty($group['Group']['picture']))
				{
					$destination = IMAGES . "groups/{$group['Group']['picture']}.png";
					if(file_exists($destination))
					{
						unlink($destination);
					}

					$destination = IMAGES . "groups/{$group['Group']['picture']}_thumb.png";
					if(file_exists($destination))
					{
						unlink($destination);
					}
				}

				$this->data['Group']['picture'] = $filename;
			}
			else
			{
				unset($this->data['Group']['picture']);
			}


			if(!$this->Group->save($this->data))
			{
				$this->cakeError('internal_error', array('action' => 'Edit', 'resource' => 'Group'));
				return;
			}

			try {
				$this->Plugin->broadcastListeners('group.edit', array(
					$group_id,
					$group['Group']['name'],
					$this->data['Group']['name'],
				));
			} catch(Exception $e) {
				$this->cakeError('internal_error', array('action' => 'Edit', 'resource' => 'Group'));
				return;
			}

			$this->Session->setFlash('Group Information Updated', 'default', array(), 'status');
			$this->redirect('/groups/edit/' . $group_id);
			return;
		}

		if($this->RequestHandler->prefers('json'))
		{
			$node = $this->Group->toNode($group);

			$response = array(
				'success' => true,
				'group' => $node,
			);
			$this->set('response', $response);
		}
	}

	/**
	 * Deletes a Group
	 *
	 * @param integer $group_id Group ID
	 */
	function delete($group_id = '')
	{
		if(!$this->RequestHandler->prefers('json'))
		{
			$this->cakeError('error404');
			return;
		}

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

		$this->Group->id = $group_id;
		if(!$this->Group->exists())
		{
			$this->cakeError('invalid_field', array('field' => 'Group ID'));
			return;
		}
		$name = $this->Group->field('name');

		$permission = $this->PermissionCmp->check('group.delete', 'group', $group_id);
		if(!$permission)
		{
			$this->cakeError('access_denied', array('action' => 'Delete', 'resource' => 'Group'));
			return;
		}

		$this->Group->delete($group_id, true);

		/* TODO: Determine if we need to delete all of the projects as well */

		try {
			$this->Plugin->broadcastListeners('group.delete', array(
				$group_id,
				$name,
			));
		} catch(Exception $e) {
			$this->cakeError('internal_error', array('action' => 'Delete', 'resource' => 'Group'));
			return;
		}

		$response = array(
			'success' => true,
		);

		$this->set('response', $response);
	}

	/**
	 * Removes a User from a Group
	 *
	 * @param integer $group_id Group ID
	 * @param integer $user_id  User ID
	 */
	function removeuser($group_id = '', $user_id = '')
	{
		if(!$this->RequestHandler->prefers('json'))
		{
			$this->cakeError('error404');
			return;
		}

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

		$group = $this->Group->find('first', array(
			'conditions' => array(
				'Group.id' => $group_id
			),
			'recursive' => -1
		));
		if(empty($group))
		{
			$this->cakeError('invalid_field', array('field' => 'Group ID'));
			return;
		}

		$user = $this->User->find('first', array(
			'conditions' => array(
				'User.id' => $user_id
			),
			'recursive' => -1
		));
		if(empty($user))
		{
			$this->cakeError('invalid_field', array('field' => 'User ID'));
			return;
		}

		$permission = $this->PermissionCmp->check('group.members.delete', 'group', $group_id);
		if(!$permission)
		{
			$this->cakeError('access_denied', array('action' => 'Remove', 'resource' => 'Group Member'));
			return;
		}

		$relationship = $this->GroupsUsers->find('first', array(
			'conditions' => array(
				'GroupsUsers.group_id' => $group_id,
				'GroupsUsers.user_id' => $user_id,
			),
			'recursive' => -1
		));
		if(empty($relationship))
		{
			$this->cakeError('invalid_field', array('field' => 'User ID'));
			return;
		}

		$this->GroupsUsers->delete($relationship['GroupsUsers']['id']);

		try {
			$this->Plugin->broadcastListeners('group.removeuser', array(
				$group_id,
				$user_id,
				$user['User']['username'],
				$group['Group']['name'],
			));
		} catch(Exception $e) {
			$this->cakeError('internal_error', array('action' => 'Remove', 'resource' => 'Group Member'));
			return;
		}

		$this->set('response', array(
			'success' => true,
		));
	}

	/**
	 * Adds a User to a Group
	 *
	 * @param integer $group_id Group ID
	 */
	function adduser($group_id = '')
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

		$conditions = array(
			'Group.id' => $group_id,
		);

		$group = $this->Group->find('first', array('conditions' => $conditions, 'recursive' => 1));
		if(empty($group))
		{
			$this->cakeError('invalid_field', array('field' => 'Group ID'));
			return;
		}

		$permission = $this->PermissionCmp->check('group.members.add', 'group', $group_id);
		if(!$permission)
		{
			$this->cakeError('access_denied', array('action' => 'Add', 'resource' => 'Group Member'));
			return;
		}

		$this->pageTitle = 'Add Group Members - ' .  $group['Group']['name'];
		$this->set('pageName', $group['Group']['name'] . ' - Add Group Members');

		$this->set('group', $group);
		$this->set('group_id', $group_id);

		$context = array(
			'group_id' => $group_id,
			'permissions' => array(
				'group' => $permission['mask'],
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

					if(!preg_match('/^(user|group|project|email):(\S+)$/', $token, $matches))
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

				/* Add users to the group. */
				if(!empty($usersToAdd))
				{
					$memberRoleId = $this->Role->field('id', array(
						'table_type' => 'group',
						'table_id' => $group_id,
						'name' => 'Member',
					));
					if(empty($memberRoleId))
					{
						$this->cakeError('internal_error', array('action' => 'Add', 'resource' => 'Group Member Role'));
						return;
					}

					foreach($usersToAdd as $user)
					{
						$data = array(
							'GroupsUsers' => array(
								'group_id' => $group_id,
								'user_id' => $user['user_id'],
								'role_id' => $memberRoleId,
							),
						);

						$this->GroupsUsers->create();

						if(!$this->GroupsUsers->save($data))
						{
							// Error on failed add.
							$this->cakeError('internal_error', array('action' => 'Add', 'resource' => 'Group Members'));
							$response['success'] = false;
							$this->set('response', $response);
							return;
						}
					}
				}

				try
				{
					$this->Plugin->broadcastListeners('group.adduser', array(
						$group_id,
						$this->Session->read('Auth.User.id'),
						$this->Session->read('Auth.User.username'),
						$this->Session->read('Auth.User.name'),
					));
				}
				catch(Exception $e)
				{
					$this->cakeError('internal_error', array('action' => 'Add User', 'resource' => 'Group'));
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
					// Contains Group and Project members
					try {
						$colleagues = $this->User->colleagues($this->Session->read('Auth.User.id'));
					} catch(Exception $e) {
						$this->cakeError('internal_error', array('action' => 'Retrieve', 'resource' => 'Colleagues'));
						return;
					}

					// Find existing members and remove them
					$conditions = array(
						'GroupsUsers.group_id' => $group_id,
					);
					$fields = array(
						'GroupsUsers.user_id',
						'GroupsUsers.role_id',
					);
					$users = $this->GroupsUsers->find('list', array('conditions' => $conditions, 'fields' => $fields));

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

					// Determine if members in the search results are already in the group.
					$existingUsers = $this->GroupsUsers->find('list', array(
						'conditions' => array(
							'GroupsUsers.group_id' => $group_id,
						),
						'fields' => array(
							'GroupsUsers.user_id',
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
	 * Leaves a Group
	 *
	 * @param integer $group_id Group ID
	 */
	function leave($group_id = '')
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

		$group = $this->Group->find('first', array(
			'conditions' => array(
				'Group.id' => $group_id
			),
			'recursive' => -1
		));
		if(empty($group))
		{
			$this->cakeError('invalid_field', array('field' => 'Group ID'));
			return;
		}

		$role = $this->get_group_role($group_id);
		if($role === false)
		{
			$this->cakeError('invalid_field', array('field' => 'Group ID'));
			return;
		}

		if($role == 'Administrator')
		{
			// TODO: Customize error message
			$this->cakeError('invalid_field', array('field' => 'Group ID'));
			return;
		}

		$relationship = $this->GroupsUsers->find('first', array(
			'conditions' => array(
				'GroupsUsers.group_id' => $group_id,
				'GroupsUsers.user_id' => $this->Session->read('Auth.User.id')
			),
			'recursive' => -1
		));
		if(empty($relationship))
		{
			$this->cakeError('invalid_field', array('field' => 'Group ID'));
			return;
		}

		$this->GroupsUsers->delete($relationship['GroupsUsers']['id']);

		try
		{
			$this->Plugin->broadcastListeners('group.removeuser', array(
				$group_id,
				$this->Session->read('Auth.User.id'),
				$this->Session->read('Auth.User.username'),
				$group['Group']['name'],
			));
		}
		catch(Exception $e)
		{
			$this->cakeError('internal_error', array('action' => 'Leave', 'resource' => 'Group'));
			return;
		}

		$this->redirect('/groups/user');
		return;
	}

	/**
	 * Lists a User's Groups
	 */
	function user()
	{
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

		$this->pageTitle = 'Groups - ' . $this->Session->read('Auth.User.name');
		$this->set('pageName', $this->Session->read('Auth.User.name') . ' - Groups');	

		if($this->RequestHandler->prefers('json'))
		{
			$groups = $this->GroupsUsers->find(
				'all',
				array(
					'conditions' => array(
						'GroupsUsers.user_id' => $this->Session->read('Auth.User.id'),
					),
					'contain' => array(
						'Group',
						'Group.User',
						'Group.Project',
						'Role.Perm',
					),
					'limit' => $limit,
					'offset' => $start,
				)
			);
			try {
				$nodes = $this->Group->toList('groups', $groups);
			} catch(Exception $e) {
				$this->cakeError('internal_error', array('action' => 'Convert', 'resource' => 'Groups'));
				return;
			}

			$this->set('nodes', $nodes);
		}
	}

	/**
	 * Lists Group Members
	 *
	 * @param integer $group_id Group ID
	 */
	function members($group_id)
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

		$group = $this->Group->find('first', array(
			'conditions' => array(
				'Group.id' => $group_id,
			),
			'recursive' => 1,
		));
		if(empty($group))
		{
			$this->cakeError('invalid_field', array('field' => 'Group ID'));
			return;
		}

		$permission = $this->PermissionCmp->check('group.members.view', 'group', $group_id);
		if(!$permission)
		{
			$this->cakeError('access_denied', array('action' => 'Manage', 'resource' => 'Group Members'));
			return;
		}

		$this->pageTitle = 'Group Members - ' . $group['Group']['name'];
		$this->set('pageName', $group['Group']['name'] . ' - Group Members');

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

							$relationship = $this->GroupsUsers->find('first', array(
								'conditions' => array(
									'GroupsUsers.group_id' => $group_id,
									'GroupsUsers.user_id' => $members['id'],
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
									'Role.table_type' => 'group',
									'Role.table_id' => $group_id,
									'Role.id' => $members['role'],
								),
							));
							if(empty($role))
							{
								$this->cakeError('invalid_field', array('field' => 'Role'));
								return;
							}

							$response['success'] = true;

							$this->GroupsUsers->id = $relationship['GroupsUsers']['id'];
							if(!$this->GroupsUsers->saveField('role_id', $members['role']))
							{
								$response['success'] = false;
							}

							try {
								$this->Plugin->broadcastListeners('group.changerole', array(
									$group_id,
									$members['id'],
									$user['User']['username'],
									$group['Group']['name'],
								));
							} catch(Exception $e) {
								$response['success'] = false;
							}
							break;
						case 'read':
							try {
								$members = $this->GroupsUsers->users($group_id);
								$response = $this->User->toList('members', $members);
							} catch(Exception $e) {
								$this->cakeError('internal_error', array('action' => 'Retrieve/Convert', 'resource' => 'Group Members'));
								return;
							}
							break;
					}
					break;
				case 'roles':
					$roles = $this->Role->find('all', array(
						'conditions' => array(
							'Role.table_type' => 'group',
							'Role.table_id' => $group_id,
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
		$this->pageTitle = 'Help - Dashboard - Group';
		$this->set('pageName', 'Group - Dashboard - Help');
	}

	/**
	 * Help for Create
	 */
	function help_create()
	{
		$this->pageTitle = 'Help - Create - Group';
		$this->set('pageName', 'Group - Create - Help');
	}

	/**
	 * Help for Edit
	 */
	function help_edit()
	{
		$this->pageTitle = 'Help - Edit - Group';
		$this->set('pageName', 'Group - Edit - Help');
	}

	/**
	 * Help for Add User
	 */
	function help_adduser()
	{
		$this->pageTitle = 'Help - Add User - Group';
		$this->set('pageName', 'Group - Add User - Help');
	}

	/**
	 * Help for User
	 */
	function help_user()
	{
		$this->pageTitle = 'Help - User - Group';
		$this->set('pageName', 'Group - User - Help');
	}

	/**
	 * Help for Members
	 */
	function help_members()
	{
		$this->pageTitle = 'Help - Members - Group';
		$this->set('pageName', 'Group - Members - Help');
	}
}
?>
