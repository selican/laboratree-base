<?php
class Project extends AppModel
{
	var $name = 'Project';

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
				'message' => 'Name must be between 2 and 255 characters.',
			),
		),
		'picture' => array(
			'rule' => array('maxLength', 32),
			'message' => 'Picture must be 32 characters or less.',
		),

	);

	var $belongsTo = array(
		'Group' => array(
			'className' => 'Group',
			'foreignKey' => 'group_id',
		),
	);

	var $hasAndBelongsToMany = array(
		'User' => array(
			'className' => 'User',
			'joinTable' => 'projects_users',
			'foreignKey' => 'project_id',
			'assocationForeignKey' => 'user_id',
			'order' => 'User.name',
			'with' => 'ProjectsUsers',
		),
	);

	var $hasMany = array(
		'Role' => array(
			'className'  => 'Role',
			'foreignKey' => 'table_id',
			'conditions' => array(
				'Role.table_type' => 'project',
			),
			'dependent'  => true,
			'exclusive'  => true,
		),
	);

	/**
	 * Converts a record to a ExtJS Store node
	 *
	 * @param array $project Project
	 * @param array $params Parameters
	 *
	 * @return array ExtJS Store Node
	 */
	function toNode($project, $params = array())
	{
		if(empty($project))
		{
			throw new InvalidArgumentException('Invalid Project');
		}

		if(!is_array($project))
		{
			throw new InvalidArgumentException('Invalid Project');
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

		if(!isset($project[$model]))
		{
			throw new InvalidArgumentException('Invalid Model Key');
		}

		$required = array(
			'id',
			'name',
		);

		foreach($required as $key)
		{
			if(!array_key_exists($key, $project[$model]))
			{
				throw new InvalidArgumentException('Missing ' . strtoupper($key) . ' Key');
			}
		}

		$node = array(
			'id' => $project[$model]['id'],
			'name' => $project[$model]['name'],
			'text' => $project[$model]['name'],
			'leaf' => true,
			'session' => 'group:project_' . $project[$model]['id'],
			'token' => 'project:' . $project[$model]['id'],
			'type' => 'project',
			'image' => '/img/projects/default_small.png',
			'role' => '',
			'permission' => array(),
			'members' => 0,
			'group_id' => '',
			'group' => '',
		);

		if(isset($project['Project']['Group']))
		{
			$project['Group'] = $project['Project']['Group'];
		}

		if(isset($project['Group']) && !empty($project['Group']))
		{
			$node['group_id'] = $project['Group']['id'];
			$node['group'] = 'Group: ' . $project['Group']['name'];
		}

		if(isset($project[$model]['picture']) && !empty($project[$model]['picture']))
		{
			$node['image'] = '/img/projects/' . $project[$model]['picture'] . '_thumb.png';
		}

		if(isset($project['User']))
		{
			$node['members'] = count($project['User']);
		}

		if(isset($project[$model]['User']))
		{
			$node['members'] = count($project[$model]['User']);
		}

		if(isset($project[$model]['CurrentUser']['Role']))
		{
			$project['Role'] = $project[$model]['CurrentUser']['Role'];
		}

		if(isset($project['Role']))
		{
			$node['role'] = $project['Role']['name'];

			if(isset($project['Role']['Perm']))
			{
				foreach($project['Role']['Perm'] as $perm)
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
