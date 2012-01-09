<?php 
/* SVN FILE: $Id$ */
/* GroupsUser Fixture generated on: 2010-12-20 14:57:37 : 1292857057*/

class GroupsUsersFixture extends CakeTestFixture {
	var $name = 'GroupsUsers';
	var $table = 'groups_users';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'group_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'user_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'role_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 2, 'key' => 'index'),
		'newrole_id' => array('type'=>'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'group_id' => array('column' => 'group_id', 'unique' => 0), 'user_id' => array('column' => 'user_id', 'unique' => 0), 'role_id' => array('column' => 'role_id', 'unique' => 0))
	);
	var $records = array(
		array(
			'id'  => 1,
			'group_id'  => 1,
			'user_id'  => 1,
			'role_id'  => 1,
			'newrole_id'  => 2
		),
		array(
			'id' => 2,
			'group_id' => 1,
			'user_id' => 2,
			'role_id' => 2,
			'newrole_id' => 3,
		),
		array(
			'id' => 3,
			'group_id' => 2,
			'user_id' => 2,
			'role_id' => 1,
			'newrole_id' => 5,
		),
		/*
		 * This relationship is to test the handling
		 * of invalid relationships when promoting
		 * or demoting.
		 */
		array(
			'id' => 4,
			'group_id' => 3,
			'user_id' => 3,
			'role_id' => 0,
			'newrole_id' => 0,
		),
		/*
		 * This relationship is to test the handling
		 * of invalid relationships when promoting
		 * or demoting.
		 */
		array(
			'id' => 5,
			'group_id' => 3,
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
			'group_id' => 3,
			'user_id' => 2,
			'role_id' => 1,
			'newrole_id' => 2,
		),
		array(
			'id' => 7,
			'group_id' => 3,
			'user_id' => 1,
			'role_id' => 1,
			'newrole_id' => 2,
		),

                array(
                        'id' => 8,
                        'group_id' => 3,
                        'user_id' => 9090,
                        'role_id' => 1,
                        'newrole_id' => 2,
                ),

	/* Added to test user model colleauge functio n*/
/*                array(
                        'id' => 8,
                        'group_id' => 1,
                        'user_id' => 5,
                        'role_id' => 2,
                        'newrole_id' => 0,
                ),
*/	);
}
?>
