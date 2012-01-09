<?php 
/* SVN FILE: $Id$ */
/* Project Fixture generated on: 2010-12-20 14:59:06 : 1292857146*/

class ProjectFixture extends CakeTestFixture {
	var $name = 'Project';
	var $table = 'projects';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'name' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'description' => array('type'=>'text', 'null' => true, 'default' => NULL),
		'privacy' => array('type'=>'string', 'null' => true, 'default' => 'private'),
		'picture' => array('type'=>'string', 'null' => true, 'default' => NULL, 'length' => 32),
		'email' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'created' => array('type'=>'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);
	var $records = array(
		array(
			'id'  => 1,
			'name'  => 'Private Test Project',
			'description'  => 'Private Test Project',
			'privacy' => 'private',
			'picture'  => null,
			'email'  => 'testprj+private@example.com',
			'created'  => '2010-12-20 14:56:15'
		),
		array(
			'id' => 2,
			'name' => 'Public Test Project',
			'description' => 'Public Test Project',
			'privacy' => 'public',
			'picture' => NULL,
			'email' => 'testprj+public@example.com',
			'created' => '2010-12-20 14:56:15'
		),
		array(
			'id' => 3,
			'name' => 'Another Private Test Project',
			'description' => 'Another Private Test Project',
			'privacy' => 'private',
			'picture' => NULL,
			'email' => 'anotherprj+private@example.com',
			'created' => '2010-12-20 14:56:15'
		),
		array(
			'id' => 4,
			'name' => 'Fourth Private Test Project',
			'description' => 'Fourth Private Test Project',
			'privacy' => 'private',
			'picture' => NULL,
			'email' => 'fourthprj+private@example.com',
			'created' => '2010-12-20 14:56:15'
		),
	);
}
?>
