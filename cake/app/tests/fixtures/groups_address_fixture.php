<?php 
/* SVN FILE: $Id$ */
/* GroupsAddress Fixture generated on: 2010-12-20 14:56:20 : 1292856980*/

class GroupsAddressFixture extends CakeTestFixture {
	var $name = 'GroupsAddress';
	var $table = 'groups_addresses';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'group_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
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
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'group_id' => array('column' => 'group_id', 'unique' => 0), 'privacy' => array('column' => 'privacy', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'group_id'  => 1,
		'address1'  => '212 W 10th St',
		'address2'  => 'Suite A470',
		'city'  => 'Indianapolis',
		'state'  => 'IN',
		'country'  => 'USA',
		'zip_code'  => '46202',
		'longitude'  => '-86.1615',
		'latitude'  => '39.7811',
		'label'  => 'Test',
		'privacy'  => 0
	));
}
?>
