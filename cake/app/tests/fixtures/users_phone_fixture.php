<?php 
/* SVN FILE: $Id$ */
/* UsersPhone Fixture generated on: 2010-12-20 15:01:49 : 1292857309*/

class UsersPhoneFixture extends CakeTestFixture {
	var $name = 'UsersPhone';
	var $table = 'users_phones';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'user_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'phone_number' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'label' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'privacy' => array('type'=>'boolean', 'null' => false, 'default' => '0'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'user_id' => array('column' => 'user_id', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'user_id'  => 1,
		'phone_number'  => '1-317-489-6818',
		'label'  => 'Test',
		'privacy'  => 0
	));
}
?>
