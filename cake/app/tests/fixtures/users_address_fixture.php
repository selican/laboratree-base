<?php 
/* SVN FILE: $Id$ */
/* UsersAddress Fixture generated on: 2010-12-20 15:01:05 : 1292857265*/

class UsersAddressFixture extends CakeTestFixture {
	var $name = 'UsersAddress';
	var $table = 'users_addresses';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'user_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'address1' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'address2' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'city' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'state' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'country' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'zip_code' => array('type'=>'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'longitude' => array('type'=>'float', 'null' => true, 'default' => NULL),
		'latitude' => array('type'=>'float', 'null' => true, 'default' => NULL),
		'label' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'privacy' => array('type'=>'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'user_id' => array('column' => 'user_id', 'unique' => 0), 'privacy' => array('column' => 'privacy', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'user_id'  => 1,
		'address1'  => '123 Test',
		'address2'  => 'Test',
		'city'  => 'Test',
		'state'  => 'IN',
		'country'  => 'USA',
		'zip_code'  => 'Test',
		'longitude'  => '-86.1009',
		'latitude'  => '39.7085',
		'label'  => 'Test',
		'privacy'  => 1
	));
}
?>
