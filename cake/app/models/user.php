<?php
class User extends AppModel
{
	var $name = 'User';

	var $validate = array(
		'username'=> array(
			'AlphaNumeric' => array(
				'rule' => 'alphaNumeric',
				'message' => 'Username must contain only letters and numbers'
			),
			'Length' => array(
				'rule' => array('between', 2, 65),
				'message' => 'Username must be between 2 and 65 characters.'
			),
			'Unique' => array(
				'rule' => 'isUnique',
				'message' => 'Username has already been taken.'
			),
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Username must not be empty.',
			),
		),
		'password' => array(
			'Length' => array(
                                'rule' => array('between', 8, 255),
                                'message' => 'Password must be between 8 and 255 characters.',
                        ),
                        'NotEmpty' => array(
                                'rule' => 'notEmpty',
                                'message' => 'Password must not be empty.',
                        ),
		),
		'email' => array(
			'NotEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Email must not be empty.',
			),
			'Valid' => array(
				'rule' => array('email', true),
				'message' => 'Email must be valid.',
			),
			'maxLength' => array(
				'rule' => array('maxLength', 255),
				'message' => 'Email must be 255 characters or less.',
			),
			'Unique' => array(
				'rule' => 'isUnique',
				'message' => 'Account with Email already exists.'
			),
		),
		'name' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Name must not be empty.',
			),
			'maxLength' => array(
				'rule' => array('maxLength', 255),
				'message' => 'Name must be 255 characters or less.',
			),
		),
		'picture' => array(
			'rule' => array('maxLength', 32),
			'message' => 'Picture must be 32 characters or less.',
		),
		'activity' => array(
			'rule' => 'notEmpty',
			'message' => 'Activity must not be empty.',
		),
		'registered' => array(
			'rule' => 'notEmpty',
			'message' => 'Registered must not be empty.',
		),
		'hash' => array(
			'rule' => array('maxLength', 40),
			'message' => 'Hash must be 40 characters or less.',
		),
		'confirmed' => array(
			'rule' => array('boolean'),
			'message' => 'Confirmed must be true (1) or false (0)',
		),
		'changepass' => array(
			'rule' => array('boolean'),
			'message' => 'Change password must be true (1) or false (0)',
		),
		'ip' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'IP address must not be empty.',
			),
			'ip' => array(
				'rule' => 'ip',
				'message' => 'IP address must be valid.',
			),
		),
		'admin' => array(
			'rule' => array('boolean'),
			'message' => 'Admin must be true (1) or false (0)',
		),
	);

	var $hasAndBelongsToMany = array(
		'Group' => array(
			'className' => 'Group',
			'joinTable' => 'groups_users',
			'foreignKey' => 'user_id',
			'associationForeignKey'	=> 'group_id',
			'order' => 'Group.name',
			'with' => 'GroupsUsers',
		),
		'Project' => array(
			'className' => 'Project',
			'joinTable' => 'projects_users',
			'foreignKey' => 'user_id',
			'associationForeignKey'	=> 'project_id',
			'order' => 'Project.name',
			'with' => 'ProjectsUsers',
		),
	);

	/**
	 * Updates the activity field for a user
	 *
	 * @param integer $user_id User ID
	 *
	 * @return boolean Success
	 */
	function activity($user_id)
	{
		if(!is_numeric($user_id) || $user_id < 1)
		{
			throw new InvalidArgumentException('Invalid user id.');
		}

		$this->id = $user_id;
		if(!$this->exists())
		{
			throw new InvalidArgumentException('Invalid user id.');
		}

		$data = array();
		$data[$this->name] = array(
			'id' => $user_id,
			'activity' => date('Y-m-d H:i:s'),
			'ip' => env('REMOTE_ADDR'),
		);

		if(!$this->save($data))
		{
			throw new RuntimeException('Unable to save activity');
		}

		return true;
	}

	/**
	 * Converts a record to a ExtJS Store node
	 *
	 * @param array $user   User
	 * @param array $params Parameters
	 *
	 * @return array ExtJS Store Node
	 */
	function toNode($user, $params = array())
	{
		if(empty($user))
		{
			throw new InvalidArgumentException('Invalid User');
		}

		if(!is_array($user))
		{
			throw new InvalidArgumentException('Invalid User');
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

		if(!isset($user[$model]))
		{
			throw new InvalidArgumentException('Invalid Model Key');
		}

		$required = array(
			'id',
			'name',
			'username',
			'activity',
		);

		foreach($required as $key)
		{
			if(!array_key_exists($key, $user[$model]))
			{
				throw new InvalidArgumentException('Missing ' . strtoupper($key) . ' Key');
			}
		}

		$node = array(
			'id' => $user[$model]['id'],
			'name' => $user[$model]['name'],
			'username' => $user[$model]['username'],
			'session' => 'user:' . $user[$model]['username'],
			'token' => 'user:' . $user[$model]['id'],
			'type' => 'user',
			'activity' => $user[$model]['activity'],
			'role_id' => 0,
			'role' => 'Unknown',
		);

		$node['image'] = '/img/users/default_small.png';
		if(isset($user[$model]['picture']) && !empty($user[$model]['picture']))
		{
			$node['image'] = '/img/users/' . $user[$model]['picture'] . '_thumb.png';
		}

		if(isset($user['Role']))
		{
			$node['role_id'] = $user['Role']['id'];
			$node['role'] = $user['Role']['name'];
		}

		return $node;
	}

	/**
	 * Returns an aggregated list of colleagues
	 *
	 * @param integer $user_id User ID
	 * @param string  $query   Query
	 * @param string  $since   Since
	 *
	 * @return array Colleagues
	 */
	function colleagues($user_id, $query = null, $since = null)
	{
		if(!is_numeric($user_id) || $user_id < 1)
		{
			throw new InvalidArgumentException('Invalid user id.');
		}

		if(!empty($query))
		{
			if(!is_string($query))
			{
				throw new InvalidArgumentException('Invalid Query');
			}
		}

		if(!empty($since))
		{
			if(!is_string($since))
			{
				throw new InvalidArgumentException('Invalid Since');
			}
		}

		$this->GroupsUsers   =& ClassRegistry::init('GroupsUsers');
		$this->ProjectsUsers =& ClassRegistry::init('ProjectsUsers');

		$unique = array();

		try {
			$groups = $this->GroupsUsers->groups($user_id);
		} catch(Exception $e) {
			throw new RuntimeException($e->getMessage());
		}
		foreach($groups as $group)
		{
			$conditions = array(
				'GroupsUsers.group_id' => $group['Group']['id'],
			);

			if(!empty($query))
			{
				$conditions['User.name LIKE'] = '%' . $query . '%';
			}

			if(!empty($since))
			{
				if(($timestamp = strtotime($since)) === false)
				{
					continue;
				}

				$conditions['User.activity >='] = date('Y-m-d H:i:s', $timestamp);
				
			}

			$users = $this->GroupsUsers->find('all', array(
				'conditions' => $conditions,
				'contain' => 'User',
			));
			foreach($users as $user)
			{
				$unique[$user['User']['id']] = $user;
			}
		}

		try {
			$projects = $this->ProjectsUsers->projects($user_id);
		} catch(Exception $e) {
			throw new RuntimeException($e->getMessage());
		}
		foreach($projects as $project)
		{
			$conditions = array(
				'ProjectsUsers.project_id' => $project['Project']['id'],
			);

			if(!empty($query))
			{
				$conditions['User.name LIKE'] = '%' . $query . '%';
			}

			if(!empty($since))
			{
				if(($timestamp = strtotime($since)) === false)
				{
					continue;
				}

				$conditions['User.activity >='] = date('Y-m-d H:i:s', $timestamp);
			}

			$users = $this->ProjectsUsers->find('all', array(
				'conditions' => $conditions,
				'contain' => 'User',
			));
			foreach($users as $user)
			{
				$unique[$user['User']['id']] = $user;
			}
		}

		unset($unique[$user_id]);

		$colleagues = array_values($unique);
		usort($colleagues, array($this, 'sort'));

		return $colleagues;
	}

	/**
	 * User Contacts
	 *
	 * @param integer $user_id User ID
	 * @param string  $query   Query
	 *
	 * @return array User Contacts
	 */
	function contacts($user_id, $query = null)
	{
		if(!is_numeric($user_id) || $user_id < 1)
		{
			throw new InvalidArgumentException('Invalid user id.');
		}

		if(!empty($query))
		{
			if(!is_string($query))
			{
				throw new InvalidArgumentException('Invalid Query');
			}
		}

		$conditions = array();
		if(!empty($query))
		{
			$conditions = array(
				$this->name . '.name LIKE' => '%' . $query . '%',
			);
		}

		$users = $this->find('all', array(
			'conditions' => $conditions,
			'recursive' => -1,
		));

		$unique = array();
		foreach($users as $user)
		{
			$unique[$user[$this->name]['id']] = $user;
		}

		unset($unique[$user_id]);

		$contacts = array_values($unique);
		usort($contacts, array($this, 'sort'));

		return $contacts;
	}

	/**
	 * Sorts user array by name
	 *
	 * @param array $a User
	 * @param array $b User
	 * 
	 * @return integer Sort Priority
	 */
	function sort($a, $b)
	{
		if(empty($a))
		{
			throw new InvalidArgumentException('Invalid User');
		}

		if(!is_array($a))
		{
			throw new InvalidArgumentException('Invalid User');
		}

		if(empty($b))
		{
			throw new InvalidArgumentException('Invblid User');
		}

		if(!is_array($b))
		{
			throw new InvalidArgumentException('Invalid User');
		}

		return strcasecmp($a[$this->name]['name'], $b[$this->name]['name']);
	}
}
?>
