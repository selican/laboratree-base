<?php
class Group extends AppModel
{
	var $name = 'Group';

	var $validate = array(
		'name' => array(
			'name-1' => array(
				'rule' => 'notEmpty',
				'message' => 'Name must not be empty.',
			),
			'name-2' => array(
				'rule' => 'isUnique',
				'message' => 'Name must be unique.',
			),
			'name-3' => array(
				'rule' => array('between', 2, 255),
				'message' => 'Name must be between 2 and 255 characters.'
			),
		),
	);

	var $hasAndBelongsToMany = array(
		'User' => array(
			'className'	=> 'User',
			'joinTable'	=> 'groups_users',
			'foreignKey'	=> 'group_id',
			'assocationForeignKey'	=> 'user_id',
			'order'         => 'User.name',
			'with'          => 'GroupsUsers',
		),
	);

	var $hasMany = array(
		'Project' => array(
			'className' => 'Project',
			'foreignKey' => 'group_id',
			'dependent' => true,
			'exclusive' => true,
		),
		'Role' => array(
			'className' => 'Role',
			'foreignKey' => 'table_id',
			'conditions' => array(
				'Role.table_type' => 'group',
			),
			'dependent' => true,
			'exclusive' => true,
		),
	);

	/**
	 * Converts a record to a ExtJS Store node
	 *
	 * @param array $group Group
	 * @param array $params Parameters
	 *
	 * @return array ExtJS Store Node
	 */
	function toNode($group, $params = array())
	{
		if(empty($group))
		{
			throw new InvalidArgumentException('Invalid Group');
		}

		if(!is_array($group))
		{
			throw new InvalidArgumentException('Invalid Group');
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

		if(!isset($group[$model]))
		{
			throw new InvalidArgumentException('Invalid Model Key');
		}

		$required = array(
			'id',
			'name',
		);

		foreach($required as $key)
		{
			if(!array_key_exists($key, $group[$model]))
			{
				throw new InvalidArgumentException('Missing ' . strtoupper($key) . ' Key');
			}
		}

		$node = array(
			'id' => $group[$model]['id'],
			'name' => $group[$model]['name'],
			'text' => $group[$model]['name'],
			'leaf' => true,
			'session' => 'group:group_' . $group[$model]['id'],
			'token' => 'group:' . $group[$model]['id'],
			'type' => 'group',
			'image' => '/img/groups/default_small.png',
			'role' => '',
			'permission' => array(),
			'members' => 0,
			'projects' => 0,
		);

		if(isset($group['Group']['picture']) && !empty($group['Group']['picture']))
		{
			$node['image'] = '/img/groups/' . $group['Group']['picture'] . '_thumb.png';
		}

		if(isset($group['User']))
		{
			$node['members'] = count($group['User']);
		}

		if(isset($group['Group']['User']))
		{
			$node['members'] = count($group['Group']['User']);
		}

		if(isset($group['Project']))
		{
			$node['projects'] = count($group['Project']);
		}

		if(isset($group['Group']['Project']))
		{
			$node['projects'] = count($group['Group']['Project']);
		}

		if(isset($group['Role']))
		{
			$node['role'] = $group['Role']['name'];

			if(isset($group['Role']['Perm']))
			{
				foreach($group['Role']['Perm'] as $perm)
				{
					if(isset($perm['RolesPermission']['mask']))
					{
						$node['permission'][$perm['name']] = $perm['RolesPermission']['mask'];
					}
				}
			}
		}

		return $node;
	}
}
?>
