<?php 
/* SVN FILE: $Id$ */
/* Group Fixture generated on: 2010-12-20 14:56:15 : 1292856975*/

class GroupFixture extends CakeTestFixture {
	var $name = 'Group';
	var $table = 'groups';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'name' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'email' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'description' => array('type'=>'text', 'null' => true, 'default' => NULL),
		'privacy' => array('type'=>'string', 'null' => true, 'default' => 'private'),
		'picture' => array('type'=>'string', 'null' => true, 'default' => NULL, 'length' => 32),
		'created' => array('type'=>'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);
	var $records = array(
		array(
			'id'  => 1,
			'name'  => 'Private Test Group',
			'email'  => 'testgrp+private@example.com',
			'description'  => 'Test Group',
			'privacy' => 'private',
			'picture'  => null,
			'created'  => '2010-12-20 14:56:15'
		),
		array(
			'id' => 2,
			'name' => 'Public Test Group',
			'email' => 'testgrp+public@example.com',
			'description' => 'Test Group',
			'privacy' => 'public',
			'picture' => NULL,
			'created' => '2010-12-20 14:56:15'
		),
		array(
			'id' => 3,
			'name' => 'Another Private Test Group',
			'email' => 'anothergrp+private@example.com',
			'description' => 'Test Group',
			'privacy' => 'private',
			'picture' => NULL,
			'created' => '2010-12-20 14:56:15'
		),
		array(
			'id' => 4,
			'name' => 'Fourth Private Test Group',
			'email' => 'fourthgrp+private@example.com',
			'description' => 'Test Group',
			'privacy' => 'private',
			'picture' => NULL,
			'created' => '2010-12-20 14:56:15'
		),
	);
}
?>
