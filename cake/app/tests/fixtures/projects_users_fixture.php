<?php 
/* SVN FILE: $Id$ */
/* ProjectsUser Fixture generated on: 2010-12-20 14:59:45 : 1292857185*/

class ProjectsUsersFixture extends CakeTestFixture {
	var $name = 'ProjectsUsers';
	var $table = 'projects_users';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'project_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'user_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'role_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 2, 'key' => 'index'),
		'newrole_id' => array('type'=>'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'project_id' => array('column' => 'project_id', 'unique' => 0), 'user_id' => array('column' => 'user_id', 'unique' => 0), 'role_id' => array('column' => 'role_id', 'unique' => 0))
	);
	var $records = array(
		array(
			'id'  => 1,
			'project_id'  => 1,
			'user_id'  => 1,
			'role_id'  => 5,
			'newrole_id'  => 8
		),
		array(
			'id' => 2,
			'project_id' => 1,
			'user_id' => 2,
			'role_id' => 6,
			'newrole_id' => 9,
		),
		array(
			'id' => 3,
			'project_id' => 2,
			'user_id' => 2,
			'role_id' => 5,
			'newrole_id' => 11,
		),
		/*
		 * This relationship is to test the handling
		 * of invalid relationships when promoting
		 * or demoting.
		 */
		array(
			'id' => 4,
			'project_id' => 3,
			'user_id' => 3,
			'role_id' => 5,
			'newrole_id' => 0,
		),
		/*
		 * This relationship is to test the handling
		 * of invalid relationships when promoting
		 * or demoting.
		 */
		array(
			'id' => 5,
			'project_id' => 3,
			'user_id' => 4,
			'role_id' => 6,
			'newrole_id' => 0,
		),
		/*
		 * This relationship is to test the
		 * promote and demote function.
		 */
		array(
			'id' => 6,
			'project_id' => 3,
			'user_id' => 2,
			'role_id' => 5,
			'newrole_id' => 14,
		),
		array(
			'id' => 7,
			'project_id' => 3,
			'user_id' => 1,
			'role_id' => 5,
			'newrole_id' => 14,
		),
	);
}
?>
