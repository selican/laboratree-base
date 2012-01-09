<?php 
/* SVN FILE: $Id$ */
/* Role Fixture generated on: 2010-12-20 14:59:52 : 1292857192*/

class RoleFixture extends CakeTestFixture {
	var $name = 'Role';
	var $table = 'roles_new';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'table_type' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'table_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10),
		'name' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'read_only' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 1),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);
	var $records = array(
		array(
			'id' => 1,
			'table_type' => 'group',
			'table_id' => 1,
			'name' => 'Administrator',
			'read_only' => 1,
		),
		array(
			'id' => 2,
			'table_type' => 'group',
			'table_id' => 1,
			'name' => 'Manager',
			'read_only' => 0,
		),
		array(
			'id' => 3,
			'table_type' => 'group',
			'table_id' => 1,
			'name' => 'Member',
			'read_only' => 0,
		),
		array(
			'id' => 4,
			'table_type' => 'group',
			'table_id' => 2,
			'name' => 'Administrator',
			'read_only' => 1,
		),
		array(
			'id' => 5,
			'table_type' => 'group',
			'table_id' => 2,
			'name' => 'Manager',
			'read_only' => 0,
		),
		array(
			'id' => 6,
			'table_type' => 'group',
			'table_id' => 2,
			'name' => 'Member',
			'read_only' => 0,
		),
		array(
			'id' => 7,
			'table_type' => 'project',
			'table_id' => 1,
			'name' => 'Administrator',
			'read_only' => 1,
		),
		array(
			'id' => 8,
			'table_type' => 'project',
			'table_id' => 1,
			'name' => 'Manager',
			'read_only' => 0,
		),
		array(
			'id' => 9,
			'table_type' => 'project',
			'table_id' => 1,
			'name' => 'Member',
			'read_only' => 0,
		),
		array(
			'id' => 10,
			'table_type' => 'project',
			'table_id' => 2,
			'name' => 'Administrator',
			'read_only' => 1,
		),
		array(
			'id' => 11,
			'table_type' => 'project',
			'table_id' => 2,
			'name' => 'Manager',
			'read_only' => 0,
		),
		array(
			'id' => 12,
			'table_type' => 'project',
			'table_id' => 2,
			'name' => 'Member',
			'read_only' => 0,
		),
		array(
			'id' => 13,
			'table_type' => 'project',
			'table_id' => 3,
			'name' => 'Administrator',
			'read_only' => 1,
		),
		array(
			'id' => 14,
			'table_type' => 'project',
			'table_id' => 3,
			'name' => 'Manager',
			'read_only' => 0,
		),
		array(
			'id' => 15,
			'table_type' => 'project',
			'table_id' => 3,
			'name' => 'Member',
			'read_only' => 0,
		),

	);
}
?>
