<?php 
/* SVN FILE: $Id$ */
/* Type Fixture generated on: 2010-12-20 15:00:34 : 1292857234*/

class TypeFixture extends CakeTestFixture {
	var $name = 'Type';
	var $table = 'types';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'table_type' => array('type'=>'string', 'null' => false, 'default' => 'user'),
		'table_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'name' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'shared' => array('type'=>'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'type' => array('type'=>'string', 'null' => false, 'default' => 'root'),
		'parent_id' => array('type'=>'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'lft' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'rght' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'table_type' => array('column' => 'table_type', 'unique' => 0), 'table_id' => array('column' => 'table_id', 'unique' => 0), 'shared' => array('column' => 'shared', 'unique' => 0), 'parent_id' => array('column' => 'parent_id', 'unique' => 0), 'lft' => array('column' => 'lft', 'unique' => 0), 'rght' => array('column' => 'rght', 'unique' => 0))
	);

	var $records = array(
		array(
			'id'  => 1,
			'table_type' => 'user',
			'table_id'  => 1,
			'name'  => 'Root',
			'shared'  => 0,
			'type' => 'root',
			'parent_id'  => NULL,
			'lft'  => 1,
			'rght'  => 6
		),
		array(
			'id' => 2,
			'table_type' => 'user',
			'table_id' => 1,
			'name' => 'User Type',
			'shared' => 0,
			'type' => 'type',
			'parent_id' => 1,
			'lft' => 2,
			'rght' => 5,
		),
		array(
			'id'  => 3,
			'table_type' => 'group',
			'table_id'  => 1,
			'name'  => 'Root',
			'shared'  => 0,
			'type' => 'root',
			'parent_id'  => NULL,
			'lft'  => 1,
			'rght'  => 6
		),
		array(
			'id'  => 4,
			'table_type' => 'group',
			'table_id'  => 1,
			'name'  => 'Test',
			'shared'  => 0,
			'type' => 'type',
			'parent_id'  => 3,
			'lft'  => 2,
			'rght'  => 5
		),
		array(
			'id' => 5,
			'table_type' => 'group',
			'table_id' => 1,
			'name' => 'SubTest',
			'shared' => 0,
			'type' => 'subtype',
			'parent_id' => 4,
			'lft' => 3,
			'rght' => 4,
		),
		array(
			'id' => 6,
			'table_type' => 'user',
			'table_id' => 2,
			'name' => 'Root',
			'shared' => 0,
			'type' => 'root',
			'parent_id' => null,
			'lft' => 1,
			'rght' => 4,
		),
		array(
			'id' => 7,
			'table_type' => 'user',
			'table_id' => 2,
			'name' => 'Shared Test',
			'shared' => 1,
			'type' => 'type',
			'parent_id' => 6,
			'lft' => 2,
			'rght' => 3,
		),
		array(
			'id' => 8,
			'table_type' => 'user',
			'table_id' => 1,
			'name' => 'User Subtype',
			'shared' => 0,
			'type' => 'type',
			'parent_id' => 2,
			'lft' => 3,
			'rght' => 4,
		),
		array(
			'id'  => 9,
			'table_type' => 'project',
			'table_id'  => 1,
			'name'  => 'Root',
			'shared'  => 0,
			'type' => 'root',
			'parent_id'  => NULL,
			'lft'  => 1,
			'rght'  => 6
		),
		array(
			'id'  => 10,
			'table_type' => 'project',
			'table_id'  => 1,
			'name'  => 'Test',
			'shared'  => 0,
			'type' => 'type',
			'parent_id'  => 9,
			'lft'  => 2,
			'rght'  => 5
		),
		array(
			'id' => 11,
			'table_type' => 'project',
			'table_id' => 1,
			'name' => 'SubTest',
			'shared' => 0,
			'type' => 'subtype',
			'parent_id' => 10,
			'lft' => 3,
			'rght' => 4,
		),
	);
}
?>
