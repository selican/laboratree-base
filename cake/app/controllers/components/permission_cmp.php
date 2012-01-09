<?php
class PermissionCmpComponent extends Object
{
	var $uses = array(
		'Perm',
		'Role',
		'GroupsUsers',
		'ProjectsUsers',
		'RolesPermissions',
	);

	var $components = array(
		'Session',
	);

	var $keywords = array(
		'document' => 'doc',
	);

	var $defaults = array(
		'group' => array(
			'group' => array(
				'Administrator' => 1023,
				'Manager' =>  635,
				'Member' => 1,
			),
			'project' => array(
				'Administrator' => 1023,
				'Manager' => 247,
				'Member' => 1,
			),
		),
		'project' => array(
			'project' => array(
				'Administrator' => 1023,
				'Manager' => 635,
				'Member' => 1,
			),
		),
	);

	function _loadModels(&$object)
	{
		foreach($object->uses as $model)
		{
			$object->{$model} =& ClassRegistry::init($model);
		}
	}

	function initialize(&$controller)
	{
		$this->Controller =& $controller;
		$this->_loadModels($this);
	}

	function startup(&$controller) {}

	/** 
	 * Returns the Permission Mask for a Feature
	 *
	 * @param string  $feature    Feature
	 * @param string  $table_type Table Type
	 * @param integer $table_id   Table ID
	 * @param integer $user_id    User ID
	 *
	 * @throws InvalidArgumentException
	 *
	 * @return integer Permission Mask
	 */
	function feature($feature, $table_type, $table_id, $user_id = '')
	{
		if(empty($feature) || !is_string($feature))
		{
			throw new InvalidArgumentException('Invalid feature.');
		}

		$perm = $this->Perm->find('first', array(
			'conditions' => array(
				'Perm.name' => $feature,
			),
			'recursive' => -1,
		));
		if(empty($perm))
		{
			throw new InvalidArgumentException('Invalid feature.');
		}

		if(empty($table_type) || !is_string($table_type) || !in_array($table_type, array('group', 'project')))
		{
			throw new InvalidArgumentException('Invalid table type.');
		}

		if(empty($table_id) || !is_numeric($table_id) || $table_id < 1)
		{
			throw new InvalidArgumentException('Invalid table id.');
		}

		if(empty($user_id))
		{
			$user_id = $this->Session->read('Auth.User.id');
		}

		if(empty($user_id) || !is_numeric($user_id) || $user_id < 1)
		{
			throw new InvalidArgumentException('Invalid user id.');
		}

		/*
		 * Get User Roles in Group or Project
		 */
		$roleId = null;
		switch($table_type)
		{
			case 'group':
				$userRole = $this->GroupsUsers->find('first', array(
					'conditions' => array(
						'GroupsUsers.group_id' => $table_id,
						'GroupsUsers.user_id' => $user_id,
					),
					'recursive' => -1,
				));
				if(empty($userRole))
				{
					throw new InvalidArgumentException('Invalid user.');
				}

				$roleId = $userRole['GroupsUsers']['role_id'];
				break;
			case 'project':
				$userRole = $this->ProjectsUsers->find('first', array(
					'conditions' => array(
						'ProjectsUsers.project_id' => $table_id,
						'ProjectsUsers.user_id' => $user_id,
					),
					'recursive' => -1,
				));
				if(empty($userRole))
				{
					throw new InvalidArgumentException('Invalid user.');
				}

				$roleId = $userRole['ProjectsUsers']['role_id'];
				break;
		}

		/*
		 * Get Permission Mask for Feature Permission/Role
		 */
		$rolePermission = $this->RolesPermissions->find('first', array(
			'conditions' => array(
				'RolesPermissions.role_id' => $roleId,
				'RolesPermissions.permission_id' => $perm['Perm']['id'],
			),
			'recursive' => -1,
		));
		if(empty($rolePermission))
		{
			return false;
		}

		$mask = intval($rolePermission['RolesPermissions']['mask']);

		return $mask;
	}

	/**
	 * Checks a Permission for a Group or Project
	 *
	 * @param string  $permission Permission Name
	 * @param string  $table_type Table Type
	 * @param integer $table_id   Table ID
	 * @param integer $user_id    User ID
	 *
	 * @throws InvalidArgumentException
	 *
	 * @return mixed Permission Status
	 */
	function check($permission, $table_type, $table_id, $user_id = '')
	{
		if(empty($permission) || !is_string($permission))
		{
			throw new InvalidArgumentException('Invalid permission.');
		}

		/*
		 * Get Function Permission (ex: discussion.add) and
		 * it's parent Feature Permission (ex: discussion)
		 */
		$perm = $this->Perm->find('first', array(
			'conditions' => array(
				'Perm.name' => $permission,
			),
			'contain' => array(
				'FeaturePermission',
			),
		));
		if(empty($perm))
		{
			throw new InvalidArgumentException('Invalid permission.');
		}

		if(empty($table_type) || !is_string($table_type) || !in_array($table_type, array('group', 'project')))
		{
			throw new InvalidArgumentException('Invalid table type.');
		}

		if(empty($table_id) || !is_numeric($table_id) || $table_id < 1)
		{
			throw new InvalidArgumentException('Invalid table id.');
		}

		if(empty($user_id))
		{
			$user_id = $this->Session->read('Auth.User.id');
		}

		if(empty($user_id) || !is_numeric($user_id) || $user_id < 1)
		{
			throw new InvalidArgumentException('Invalid user id.');
		}

		/*
		 * Get User Roles in Group or Project
		 */
		$roleId = null;
		switch($table_type)
		{
			case 'group':
				$userRole = $this->GroupsUsers->find('first', array(
					'conditions' => array(
						'GroupsUsers.group_id' => $table_id,
						'GroupsUsers.user_id' => $user_id,
					),
					'recursive' => -1,
				));
				if(empty($userRole))
				{
					throw new InvalidArgumentException('Invalid user.');
				}

				$roleId = $userRole['GroupsUsers']['role_id'];
				break;
			case 'project':
				$userRole = $this->ProjectsUsers->find('first', array(
					'conditions' => array(
						'ProjectsUsers.project_id' => $table_id,
						'ProjectsUsers.user_id' => $user_id,
					),
					'recursive' => -1,
				));
				if(empty($userRole))
				{
					throw new InvalidArgumentException('Invalid user.');
				}

				$roleId = $userRole['ProjectsUsers']['role_id'];
				break;
		}

		/*
		 * Get Permission Mask for Feature Permission/Role
		 */
		$rolePermission = $this->RolesPermissions->find('first', array(
			'conditions' => array(
				'RolesPermissions.role_id' => $roleId,
				'RolesPermissions.permission_id' => $perm['FeaturePermission']['id'],
			),
			'recursive' => -1,
		));
		if(empty($rolePermission))
		{
			return false;
		}

		/*
		 * Check if the Function Permission Mask is in the
		 * Feature Permission/Role Mask, and return the
		 * Feature Permission/Role Mask if true
		 */
		$feature = intval($perm['Perm']['mask']);
		$mask = intval($rolePermission['RolesPermissions']['mask']);

		if($feature & $mask)
		{
			return array(
				'feature' => $feature,
				'mask' => $mask,
				'role' => $roleId
			);
		}

		return false;
	}

	/**
	 * Recursively converts Permissions to a Tree
	 *
	 * @param array   $permissions Permissions
	 * @param array   $nodes       Tree Nodes
	 * @param integer $value       Permission Mask
	 *
	 * @throws InvalidArgumentException
	 */
	function toTree(&$permissions, &$nodes, $value)
	{
		if(!empty($permissions))
		{
			if(!is_array($permissions))
			{
				throw new InvalidArgumentException('Invalid Permissions');
			}
		}

		if(!empty($nodes))
		{
			if(!is_array($nodes))
			{
				throw new InvalidArgumentException('Invalid Nodes');
			}
		}

		if(!empty($value))
		{
			if(!is_numeric($value))
			{
				throw new InvalidArgumentException('Invalid Value');
			}
		}

		$node = array_shift($nodes);

		if(!array_key_exists($node, $permissions))
		{
			$permissions[$node] = array();
		}

		if(count($nodes) > 0)
		{
			$this->toTree($permissions[$node], $nodes, $value);
		}
		else
		{
			$permissions[$node] = $value;
		}
	}

	/**
	 * Recursively converts Permissions to JSON
	 *
	 * @param array   $permissions Permissions
	 * @param string  $json        JSON
	 * @param integer $level       Indent Level
	 *
	 * @throws InvalidArgumentException
	 */
	function toJSON($permissions, &$json, $level)
	{
		if(!empty($permissions))
		{
			if(!is_array($permissions))
			{
				throw new InvalidArgumentException('Invalid Permissions');
			}
		}

		if(!empty($json))
		{
			if(!is_string($json))
			{
				throw new InvalidArgumentException('Invalid JSON');
			}
		}

		if(!empty($level))
		{
			if(!is_numeric($level))
			{
				throw new InvalidArgumentException('Invalid Level');
			}
		}

		foreach($permissions as $permission => $value)
		{
			$spacing = '';
			for($i = $level; $i > 0; $i--)
			{
				$spacing .= "\t";
			}

			if(array_key_exists($permission, $this->keywords))
			{
				$permission = $this->keywords[$permission];
			}

			if(is_array($value))
			{
				$json .= $spacing . "'" . $permission . "': {\n";
				$this->toJSON($value, $json, $level + 1);
				$json .= $spacing . "},\n";
			}
			else
			{
				$json .= $spacing . "'" . $permission . "': " . $value . ",\n";
			}
		}
	}

	/**
	 * Sets up the initial permissions for a newly created Group or Project
	 *
	 * @param string  $table_type Table Type
	 * @param integer $table_id   Table ID
	 *
	 * @throws InvalidArgumentException
	 * @throws RuntimeException
	 */
	function setup($table_type, $table_id, $defaults = array())
	{
		if(empty($table_type) || !is_string($table_type) || !in_array($table_type, array('group', 'project')))
		{
			throw new InvalidArgumentException('Invalid table type.');
		}

		if(empty($table_id) || !is_numeric($table_id) || $table_id < 1)
		{
			throw new InvalidArgumentException('Invalid table id.');
		}

		if(empty($defaults))
		{
			$defaults = $this->defaults;
		}

		if(!is_array($defaults))
		{
			throw new InvalidArgumentException('Invalid Defaults');
		}

		if(!isset($defaults[$table_type]))
		{
			throw new RuntimeException('Unable to retrieve defaults for table type');
		}
		
		foreach($defaults[$table_type] as $feature => $roles)
		{
			$perm = $this->Perm->field('id', array(
				'name' => $feature,
				'mask' => null,
			));
			if(empty($perm))
			{
				throw new RuntimeException('Unable to retreive feature permission');
			}

			foreach($roles as $role => $mask)
			{
				$role = $this->Role->field('id', array(
					'table_type' => $table_type,
					'table_id' => $table_id,
					'name' => $role,
				));
				if(empty($role))
				{
					throw new RuntimeException('Unable to retrieve role');
				}

				$id = $this->RolesPermissions->field('id', array(
					'RolesPermissions.role_id' => $role,
					'RolesPermissions.permission_id' => $perm,
				));

				if(!empty($id))
				{
					continue;
				}

				$data = array(
					'RolesPermissions' => array(
						'role_id' => $role,
						'permission_id' => $perm,
						'mask' => $mask,
					),
				);
				$this->RolesPermissions->create();
				if(!$this->RolesPermissions->save($data))
				{
					throw new RuntimeException('Unable to save permissions');
				}
			}
		}
	}
}
?>
