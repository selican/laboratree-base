<?php
class ProjectsUsers extends AppModel
{
	var $name = 'ProjectsUsers';

	var $validate = array(
		'project_id' => array(
			'project_id-1' => array(
				'rule' => 'notEmpty',
				'message' => 'Project ID must not be empty.',
			),
			'project_id-2' => array(
				'rule' => 'numeric',
				'message' => 'Project ID must be a number.',
			),
			'project_id-3' => array(
				'rule' => array('maxLength', 10),
				'message' => 'Project ID must be 10 characters or less.',
			),
		),
		'user_id' => array(
			'user_id-1' => array(
				'rule' => 'notEmpty',
				'message' => 'User ID must not be empty.',
			),
			'user_id-2' => array(
				'rule' => 'numeric',
				'message' => 'User ID must be a number.',
			),
			'user_id-3' => array(
				'rule' => array('maxLength', 10),
				'message' => 'User ID must be 10 characters or less.',
			),
		),
		'role_id' => array(
			'role_id-1' => array(
				'rule' => 'notEmpty',
				'message' => 'Role ID must not be empty.',
			),
			'role_id-2' => array(
				'rule' => 'numeric',
				'message' => 'Role ID must be a number.',
			),
			'role_id-3' => array(
				'rule' => array('maxLength', 10),
				'message' => 'Role ID must be 10 characters or less.',
			),
		),
	);

	var $belongsTo = array(
		'User' => array(
			'className'=> 'User',
			'foreignKey' => 'user_id',
		),
		'Project' => array(
			'className' => 'Project',
			'foreignKey' => 'project_id',
		),
		'Role' => array(
			'className' => 'Role',
			'foreignKey' => 'role_id',
		),
	);

	/**
	 * Returns an array of projects for a user
	 *
	 * @param integer $user_id   User ID
	 *
	 * @return array User Projects
	 */
	function projects($user_id)
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
				'User',
				'Project',
				'Project.User',
				'Project.Group',
				'Role',
			),
			'order' => 'Project.name',
		));
	}

	/**
	 * Returns an array of users for a project
	 *
	 * @param integer $project_id Project ID
	 *
	 * @return array Project Users
	 */
	function users($project_id)
	{
		if(!is_numeric($project_id) || $project_id < 1)
		{
			throw new InvalidArgumentException('Invalid project id.');
		}

		return $this->find('all', array(
			'conditions' => array(
				$this->name . '.project_id' => $project_id,
			),
			'contain' => array(
				'User',
				'Project',
				'Role',
			),
			'order' => 'User.name',
		));
	}
}
?>
