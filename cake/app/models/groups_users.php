<?php
class GroupsUsers extends AppModel
{
	var $name = 'GroupsUsers';

	var $validate = array(
		'group_id' => array(
			'rule' => 'numeric',
			'message' => 'Group ID must be a number.',
		),
		'user_id' => array(
			'rule' => 'numeric',
			'message' => 'User ID must be a number.',
		),
		'role_id' => array(
			'rule' => 'numeric',
			'message' => 'Role ID must be a number.',
		),
	);

	var $belongsTo = array(
		'User' => array(
			'className'=> 'User',
			'foreignKey' => 'user_id',
		),
		'Group' => array(
			'className' => 'Group',
			'foreignKey' => 'group_id',
		),
		'Role' => array(
			'className' => 'Role',
			'foreignKey' => 'role_id',
		),
	);

	/**
	 * Returns an array of groups for a user
	 *
	 * @param integer $user_id   User ID
	 *
	 * @return array User Groups
	 */
	function groups($user_id)
	{
		if(!is_numeric($user_id) || $user_id < 1)
		{
			throw new InvalidArgumentException('Invalid user id.');
		}

		return $this->find('all', array(
			'conditions' => array(
				$this->name . '.user_id' => $user_id,
			),
			'contain' => array(
				'Group',
				'Group.Project',
				'Group.User',
				'Role',
			),
			'order' => 'Group.name',
		));
	}

	/**
	 * Returns an array of users for a group
	 *
	 * @param integer $group_id  Group ID
	 *
	 * @return array Project Users
	 */
	function users($group_id)
	{
		if(!is_numeric($group_id) || $group_id < 1)
		{
			throw new InvalidArgumentException('Invalid group id.');
		}

		return $this->find('all', array(
			'conditions' => array(
				$this->name . '.group_id' => $group_id,
			),
			'contain' => array(
				'User',
				'Group',
				'Role',
			),
			'order' => 'User.name',
		));
	}
}
?>
