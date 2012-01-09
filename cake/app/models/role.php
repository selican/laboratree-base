<?php
class Role extends AppModel
{
	var $name = 'Role';

	var $hasAndBelongsToMany = array(
		'Perm' => array(
			'className' => 'Perm',
			'joinTable' => 'roles_permissions',
			'foreignKey' => 'role_id',
			'associationForeignKey' => 'permission_id',
			'with' => 'RolesPermission',
		),
	);

	var $belongsTo = array(
		'Group' => array(
			'className' => 'Group',
			'foreignKey' => 'table_id',
		),
		'Project' => array(
			'className' => 'Project',
			'foreignKey' => 'table_id',
		),
	);

	/**
	 * Converts a record to a ExtJS Store node
	 *
	 * @param array $group Role
	 * @param array $params Parameters
	 *
	 * @return array ExtJS Store Node
	 */
	function toNode($role, $params = array())
	{
		if(empty($role))
		{
			throw new InvalidArgumentException('Invalid Role');
		}

		if(!is_array($role))
		{
			throw new InvalidArgumentException('Invalid Permission');
		}

		if(!empty($params))
		{
			if(!is_array($params))
			{
				throw new InvalidArgumentException('Invalid Parameters');
			}
		}

		if(!isset($params['model']))
		{
			$params['model'] = $this->name;
		}

		if(!is_string($params['model']))
		{
			throw new RuntimeException('Invalid Model');
		}

		$model = $params['model'];

		if(!isset($role[$model]))
		{
			throw new InvalidArgumentException('Invalid Model Key');
		}

		$required = array(
			'id',
			'table_type',
			'table_id',
			'name',
		);

		foreach($required as $key)
		{
			if(!array_key_exists($key, $role[$model]))
			{
				throw new InvalidArgumentException('Missing ' . strtoupper($key) . ' Key');
			}
		}

		$node = array(
			'id' => $role[$model]['id'],
			'table_type' => $role[$model]['table_type'],
			'table_id' => $role[$model]['table_id'],
			'name' => $role[$model]['name'],
			'value' => 0,
		);

		if(isset($role['RolesPermissions']))
		{
			$node['value'] = $role['RolesPermissions']['value'];
		}

		return $node;
	}
}
?>
