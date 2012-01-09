<?php 
/* SVN FILE: $Id$ */
/* UsersJob Fixture generated on: 2010-12-20 15:01:42 : 1292857302*/

class UsersJobFixture extends CakeTestFixture {
	var $name = 'UsersJob';
	var $table = 'users_jobs';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'user_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'name' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'title' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'start_date' => array('type'=>'date', 'null' => true, 'default' => NULL),
		'end_date' => array('type'=>'date', 'null' => true, 'default' => NULL),
		'privacy' => array('type'=>'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'user_id' => array('column' => 'user_id', 'unique' => 0), 'privacy' => array('column' => 'privacy', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'user_id'  => 1,
		'name'  => 'Test',
		'title'  => 'Test',
		'start_date'  => '2000-02-25',
		'end_date'  => null,
		'privacy'  => 0
	));
}
?>
