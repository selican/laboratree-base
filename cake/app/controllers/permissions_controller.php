<?php
class PermissionsController extends AppController
{
	var $name = 'Permissions';

	var $uses = array(
		'Group',
		'Project',
		'Perm',
		'GroupsUsers',
		'ProjectsUsers',
		'Role',
		'RolesPermissions',
	);

	var $components = array(
		'Auth',
		'Security',
		'Session',
		'RequestHandler',
		'PermissionCmp',
		'Plugin',
	);

	function beforeFilter()
	{
		$this->Security->validatePost = false;

		parent::beforeFilter();
	}

	/**
	 * Redirects to the correct action based on Table Type
	 *
	 * @param string  $table_type Table Type
	 * @param integer $table_id   Table ID
	 */
	function index($table_type = '', $table_id = '')
	{
		if(empty($table_type))
		{
			$this->cakeError('missing_field', array('field' => 'Table Type'));
			return;
		}	

		if(!is_string($table_type) || !in_array($table_type, array('group', 'project')))
		{
			$this->cakeError('invalid_field', array('field' => 'Table Type'));
			return;
		}

		if(empty($table_id))
		{
			$this->cakeError('missing_field', array('field' => 'Table ID'));
			return;
		}

		if(!is_numeric($table_id) || $table_id < 1)
		{
			$this->cakeError('invalid_field', array('field' => 'Table ID'));
			return;
		}

		if(method_exists($this, $table_type))
		{
			$this->redirect("/permissions/$table_type/$table_id");
			return;
		}

		$this->cakeError('invalid_field', array('field' => 'Table Type'));
		return;
	}

	/**
	 * Allows a Group Members with the correct Permission to manage Group Permissions
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

		$permission = $this->PermissionCmp->check('group.permissions', 'group', $group_id);
		if(!$permission)
		{
			$this->cakeError('access_denied', array('action' => 'View', 'resource' => 'Group Roles'));
			return;
		}

		$this->pageTitle = 'Group Permissions - ' . $group['Group']['name'];
		$this->set('pageName', $group['Group']['name'] . ' - Group Permissions');

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
			foreach($this->data['RolesPermissions'] as $role_id => $features)
			{
				$this->RolesPermissions->deleteAll(array(
					'RolesPermissions.role_id' => $role_id,
				));

				foreach($features as $feature_id => $permissions)
				{
					$feature = 0;

					foreach($permissions as $permission_id => $value)
					{
						if(!$value)
						{
							continue;
						}

						$this->Perm->id = $permission_id;
						$mask = $this->Perm->field('mask');

						$feature |= $mask;
					}

					$data = array(
						'RolesPermissions' => array(
							'role_id' => $role_id,
							'permission_id' => $feature_id,
							'mask' => $feature,
						),
					);
					$this->RolesPermissions->create();
					if(!$this->RolesPermissions->save($data))
					{
						$this->cakeError('internal_error', array('action' => 'Save', 'resource' => 'Permissions'));
						return;
					}
				}
			}

			$this->Session->setFlash('Permissions Updated', 'default', array(), 'status');
			$this->redirect('/permissions/group/' . $group_id);
			return;
		}

		if($this->RequestHandler->prefers('json'))
		{
			$roles = $this->Role->find('all', array(
				'conditions' => array(
					'Role.table_type' => 'group',
					'Role.table_id' => $group_id,
				),
				'order' => 'Role.id ASC',
				'recursive' => -1,
			));
			if(empty($roles))
			{
				$this->cakeError('internal_error', array('action' => 'Retrieve', 'resource' => 'Roles'));
				return;
			}

			$root = $this->Perm->find('first', array(
				'conditions' => array(
					'Perm.parent_id' => null,
				),
				'recursive' => -1,
			));
			if(empty($root))
			{
				$this->cakeError('internal_error', array('action' => 'Retrieve', 'resource' => 'Permission Root'));
				return;
			}

			$features = $this->Perm->find('threaded', array(
				'conditions' => array(
					'Perm.lft >' => $root['Perm']['lft'],
					'Perm.rght <' => $root['Perm']['rght'],
				),
				'recursive' => -1,
			));
			if(empty($features))
			{
				$this->cakeError('internal_error', array('action' => 'Retrieve', 'resource' => 'Permission Features'));
				return;
			}

			$response = array(
				'success' => true,
				'roles' => array(),
			);
			foreach($roles as $role)
			{
				$sections = array();
				foreach($features as $feature)
				{
					if(empty($feature['children']))
					{
						continue;
					}

					if(!array_key_exists($feature['Perm']['id'], $sections))
					{
						$sections[$feature['Perm']['id']] = array(
							'id' => $feature['Perm']['id'],
							'name' => $feature['Perm']['title'],
							'permissions' => array()
						);
					}

					$mask = $this->RolesPermissions->find('first', array(
						'conditions' => array(
							'RolesPermissions.role_id' => $role['Role']['id'],
							'RolesPermissions.permission_id' => $feature['Perm']['id'],
						),
						'fields' => array(
							'RolesPermissions.mask',
						),
						'recursive' => -1,
					));

					foreach($feature['children'] as $permission)
					{
						$value = 0;
						if(!empty($mask))
						{
							$p = intval($permission['Perm']['mask']);
							$m = intval($mask['RolesPermissions']['mask']);

							if($p & $m)
							{
								$value = 1;
							}
						}

						$sections[$feature['Perm']['id']]['permissions'][] = array(
							'id' => $permission['Perm']['id'],
							'name' => $permission['Perm']['name'],
							'title' => $permission['Perm']['title'],
							'value' => $value,
						);
					}
				}

				$response['roles'][] = array(
					'id' => $role['Role']['id'],
					'name' => $role['Role']['name'],
					'read_only' => $role['Role']['read_only'],
					'features' => array_values($sections),
				);
			}

			$this->set('response', $response);
		}
	}

	/**
	 * Allows a Project Members with the correct Permission to manage Project Permissions
	 *
	 * @param integer $project_id Project ID
	 */
	function project($project_id = '')
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

		$permission = $this->PermissionCmp->check('project.permissions', 'project', $project_id);
		if(!$permission)
		{
			$this->cakeError('access_denied', array('action' => 'View', 'resource' => 'Project Roles'));
			return;
		}

		$this->pageTitle = 'Project Permissions - ' . $project['Project']['name'];
		$this->set('pageName', $project['Project']['name'] . ' - Project Permissions');

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

		if(!empty($this->data))
		{
			foreach($this->data['RolesPermissions'] as $role_id => $features)
			{
				$this->RolesPermissions->deleteAll(array(
					'RolesPermissions.role_id' => $role_id,
				));

				foreach($features as $feature_id => $permissions)
				{
					$feature = 0;

					foreach($permissions as $permission_id => $value)
					{
						if(!$value)
						{
							continue;
						}

						$this->Perm->id = $permission_id;
						$mask = $this->Perm->field('mask');

						$feature |= $mask;
					}

					$data = array(
						'RolesPermissions' => array(
							'role_id' => $role_id,
							'permission_id' => $feature_id,
							'mask' => $feature,
						),
					);
					$this->RolesPermissions->create();
					if(!$this->RolesPermissions->save($data))
					{
						$this->cakeError('internal_error', array('action' => 'Save', 'resource' => 'Permissions'));
						return;
					}
				}
			}

			$this->Session->setFlash('Permissions Updated', 'default', array(), 'status');
			$this->redirect('/permissions/project/' . $project_id);
			return;
		}

		if($this->RequestHandler->prefers('json'))
		{
			$roles = $this->Role->find('all', array(
				'conditions' => array(
					'Role.table_type' => 'project',
					'Role.table_id' => $project_id,
				),
				'order' => 'Role.id ASC',
				'recursive' => -1,
			));
			if(empty($roles))
			{
				$this->cakeError('internal_error', array('action' => 'Retrieve', 'resource' => 'Roles'));
				return;
			}

			$root = $this->Perm->find('first', array(
				'conditions' => array(
					'Perm.parent_id' => null,
				),
				'recursive' => -1,
			));
			if(empty($root))
			{
				$this->cakeError('internal_error', array('action' => 'Retrieve', 'resource' => 'Permission Root'));
				return;
			}

			$features = $this->Perm->find('threaded', array(
				'conditions' => array(
					'Perm.lft >' => $root['Perm']['lft'],
					'Perm.rght <' => $root['Perm']['rght'],
				),
				'recursive' => -1,
			));
			if(empty($features))
			{
				$this->cakeError('internal_error', array('action' => 'Retrieve', 'resource' => 'Permission Features'));
				return;
			}

			$response = array(
				'success' => true,
				'roles' => array(),
			);
			foreach($roles as $role)
			{
				$sections = array();
				foreach($features as $feature)
				{
					if(empty($feature['children']))
					{
						continue;
					}

					if(!array_key_exists($feature['Perm']['id'], $sections))
					{
						$sections[$feature['Perm']['id']] = array(
							'id' => $feature['Perm']['id'],
							'name' => $feature['Perm']['title'],
							'permissions' => array()
						);
					}

					$mask = $this->RolesPermissions->find('first', array(
						'conditions' => array(
							'RolesPermissions.role_id' => $role['Role']['id'],
							'RolesPermissions.permission_id' => $feature['Perm']['id'],
						),
						'fields' => array(
							'RolesPermissions.mask',
						),
						'recursive' => -1,
					));

					foreach($feature['children'] as $permission)
					{
						$value = 0;
						if(!empty($mask))
						{
							$p = intval($permission['Perm']['mask']);
							$m = intval($mask['RolesPermissions']['mask']);

							if($p & $m)
							{
								$value = 1;
							}
						}

						$sections[$feature['Perm']['id']]['permissions'][] = array(
							'id' => $permission['Perm']['id'],
							'name' => $permission['Perm']['name'],
							'title' => $permission['Perm']['title'],
							'value' => $value,
						);
					}
				}

				$response['roles'][] = array(
					'id' => $role['Role']['id'],
					'name' => $role['Role']['name'],
					'read_only' => $role['Role']['read_only'],
					'features' => array_values($sections),
				);
			}

			$this->set('response', $response);
		}
	}

	/**
	 * Adds a Role to the Group or Project
	 *
	 * @param string  $table_type Table Type
	 * @param integer $table_id   Table ID
	 */
	function add($table_type = '', $table_id = '')
	{
		if(!$this->RequestHandler->prefers('json'))
		{
			$this->cakeError('error404');
			return;
		}

		if(empty($table_type))
		{
			$this->cakeError('missing_field', array('field' => 'Table Type'));
			return;
		}	

		if(!is_string($table_type) || !in_array($table_type, array('group', 'project')))
		{
			$this->cakeError('invalid_field', array('field' => 'Table Type'));
			return;
		}

		if(empty($table_id))
		{
			$this->cakeError('missing_field', array('field' => 'Table ID'));
			return;
		}

		if(!is_numeric($table_id) || $table_id < 1)
		{
			$this->cakeError('invalid_field', array('field' => 'Table ID'));
			return;
		}

		if(!isset($this->params['form']['role']))
		{
			$this->cakeError('missing_field', array('field' => 'Role'));
			return;
		}

		$permission = $this->PermissionCmp->check($table_type . '.permissions', $table_type, $table_id);
		if(!$permission)
		{
			$this->cakeError('access_denied', array('action' => 'Add', 'resource' => 'Role'));
			return;
		}

		$role = array(
			'Role' => array(
				'table_type' => $table_type,
				'table_id' => $table_id,
				'name' => $this->params['form']['role'],
			),
		);
		$this->Role->create();
		if(!$this->Role->save($role))
		{
			$this->cakeError('internal_error', array('action' => 'Save', 'resource' => 'Role'));
			return;
		}
		$role['Role']['id'] = $this->Role->id;

		$permissions = $this->Perm->find('all');
		if(empty($permissions))
		{
			$this->cakeError('internal_error', array('action' => 'Retrieve', 'resource' => 'Permissions'));
			return;
		}

		$features = array();
		foreach($permissions as $permission)
		{
			list($feature, $name) = explode('.', $permission['Perm']['name']);
			if(!array_key_exists($feature, $features))
			{
				$features[$feature] = array(
					'name' => ucfirst($feature),
					'permissions' => array()
				);
			}

			$features[$feature]['permissions'][] = array(
				'id' => $permission['Perm']['id'],
				'name' => $permission['Perm']['name'],
				'title' => $permission['Perm']['title'],
				'value' => 0,
			);
		}

		$response = array(
			'success' => true,
			'role' => array(
				'id' => $role['Role']['id'],
				'name' => $role['Role']['name'],
				'features' => array_values($features),
			),
		);
		$this->set('response', $response);
	}

	/**
	 * Edits a Group or Project Role
	 *
	 * @param integer $role_id Role ID
	 */
	function edit($role_id = '')
	{
		if(!$this->RequestHandler->prefers('json'))
		{
			$this->cakeError('error404');
			return;
		}

		if(empty($role_id))
		{
			$this->cakeError('missing_field', array('field' => 'Role ID'));
			return;
		}

		if(!is_numeric($role_id) || $role_id < 1)
		{
			$this->cakeError('invalid_field', array('field' => 'Role ID'));
			return;
		}

		if(!isset($this->params['form']['role']))
		{
			$this->cakeError('missing_field', array('field' => 'Role'));
			return;
		}

		$role = $this->Role->find('first', array(
			'conditions' => array(
				'Role.id' => $role_id,
			),
			'recursive' => -1,
		));
		if(empty($role))
		{
			$this->cakeError('invalid_field', array('field' => 'ID'));
			return;
		}

		if($role['Role']['read_only'])
		{
			$this->cakeError('invalid_field', array('field' => 'ID'));
			return;
		}

		$permission = $this->PermissionCmp->check($role['Role']['table_type'] . '.permissions', $role['Role']['table_type'], $role['Role']['table_id']);
		if(!$permission)
		{
			$this->cakeError('access_denied', array('action' => 'Edit', 'resource' => 'Role'));
			return;
		}

		$response = array(
			'success' => true,
		);

		$this->Role->id = $role_id;
		if(!$this->Role->saveField('name', $this->params['form']['role']))
		{
			$response['success'] = false;
		}
		$this->set('response', $response);
	}

	/**
	 * Deletes a Group or Project Role
	 *
	 * @param integer $role_id Role ID
	 */
	function delete($role_id = '')
	{
		if(!$this->RequestHandler->prefers('json'))
		{
			$this->cakeError('error404');
			return;
		}

		if(empty($role_id))
		{
			$this->cakeError('missing_field', array('field' => 'Role ID'));
			return;
		}

		if(!is_numeric($role_id) || $role_id < 1)
		{
			$this->cakeError('invalid_field', array('field' => 'Role ID'));
			return;
		}

		$role = $this->Role->find('first', array(
			'conditions' => array(
				'Role.id' => $role_id,
			),
			'recursive' => -1,
		));
		if(empty($role))
		{
			$this->cakeError('invalid_field', array('field' => 'ID'));
			return;
		}

		if($role['Role']['read_only'])
		{
			$this->cakeError('invalid_field', array('field' => 'ID'));
			return;
		}

		$permission = $this->PermissionCmp->check($role['Role']['table_type'] . '.permissions', $role['Role']['table_type'], $role['Role']['table_id']);
		if(!$permission)
		{
			$this->cakeError('access_denied', array('action' => 'Delete', 'resource' => 'Role'));
			return;
		}

		$member = $this->Role->find('first', array(
			'conditions' => array(
				'Role.table_type' => $role['Role']['table_type'],
				'Role.table_id' => $role['Role']['table_id'],
				'Role.name' => 'Member',
			),
			'recursive' => -1,
		));
		if(empty($member))
		{
			$this->cakeError('internal_error', array('action' => 'Retrieve', 'resource' => 'Member Role'));
			return;
		}

		switch($role['Role']['table_type'])
		{
			case 'group':
				$users = $this->GroupsUsers->find('all', array(
					'conditions' => array(
						'GroupsUsers.group_id' => $role['Role']['table_id'],
						'GroupsUsers.role_id' => $role_id,
					),
					'recursive' => -1,
				));
				foreach($users as $user)
				{
					$this->GroupsUsers->id = $user['GroupsUsers']['id'];
					$this->GroupsUsers->saveField('role_id', $member['Role']['id']);
				}
				break;
			case 'project':
				$users = $this->ProjectsUsers->find('all', array(
					'conditions' => array(
						'ProjectsUsers.project_id' => $role['Role']['table_id'],
						'ProjectsUsers.role_id' => $role_id,
					),
					'recursive' => -1,
				));
				foreach($users as $user)
				{
					$this->ProjectsUsers->id = $user['ProjectsUsers']['id'];
					$this->ProjectsUsers->saveField('role_id', $member['Role']['id']);
				}
				break;
		}

		$response = array(
			'success' => true,
		);

		if(!$this->Role->delete($role_id))
		{
			$response['success'] = false;
		}

		$this->set('response', $response);
	}
}
?>
