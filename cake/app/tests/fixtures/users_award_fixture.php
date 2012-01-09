<?php 
/* SVN FILE: $Id$ */
/* UsersAward Fixture generated on: 2010-12-20 15:01:16 : 1292857276*/

class UsersAwardFixture extends CakeTestFixture {
	var $name = 'UsersAward';
	var $table = 'users_awards';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'user_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'label' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'award' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'privacy' => array('type'=>'boolean', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'user_id' => array('column' => 'user_id', 'unique' => 0), 'privacy' => array('column' => 'privacy', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'user_id'  => 1,
		'label'  => 'Test',
		'award'  => 'Test',
		'privacy'  => 1
	));
}
?>
