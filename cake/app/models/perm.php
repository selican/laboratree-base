<?php
class Perm extends AppModel
{
	var $name = 'Perm';
	var $actsAs = array(
		'Tree',
		'Containable',
	);
	var $useTable = 'permissions';

	var $hasAndBelongsToMany = array(
		'Role' => array(
			'className' => 'Role',
			'joinTable' => 'roles_permissions',
			'foreignKey' => 'permission_id',
			'associationForeignKey' => 'role_id',
			'with' => 'RolesPermissions',
		),
	);

	var $belongsTo = array(
		'FeaturePermission' => array(
			'className' => 'Perm',
			'foreignKey' => 'parent_id',
		),
	);

	var $hasMany = array(
		'FunctionPermission' => array(
			'className' => 'Perm',
			'foreignKey' => 'parent_id',
		),
	);

	/**
	 * Converts a record to a ExtJS Store node
	 *
	 * @param array  $group Permission
	 * @param array $params Parameters
	 *
	 * @return array ExtJS Store Node
	 */
	function toNode($perm, $params = array())
	{
		if(empty($perm))
		{
			throw new InvalidArgumentException('Invalid Permission');
		}

		if(!is_array($perm))
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

		if(!isset($perm[$model]))
		{
			throw new InvalidArgumentException('Invalid Model Key');
		}

		$required = array(
			'id',
			'name',
			'title',
		);

		foreach($required as $key)
		{
			if(!array_key_exists($key, $perm[$model]))
			{
				throw new InvalidArgumentException('Missing ' . strtoupper($key) . ' Key');
			}
		}

		$node = array(
			'id' => $perm[$model]['id'],
			'name' => $perm[$model]['name'],
			'title' => $perm[$model]['title'],
			'text' => $perm[$model]['title'],
		);

		return $node;
	}
}
?>
