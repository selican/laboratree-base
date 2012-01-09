<?php 
/* SVN FILE: $Id$ */
/* UsersUrl Fixture generated on: 2010-12-20 15:02:11 : 1292857331*/

class UsersUrlFixture extends CakeTestFixture {
	var $name = 'UsersUrl';
	var $table = 'users_urls';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'user_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'link' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'label' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'privacy' => array('type'=>'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'user_id' => array('column' => 'user_id', 'unique' => 0), 'privacy' => array('column' => 'privacy', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'user_id'  => 1,
		'link'  => 'http://example.com',
		'label'  => 'Test',
		'privacy'  => 0
	));
}
?>
