<?php 
/* SVN FILE: $Id$ */
/* GroupsPhone Fixture generated on: 2010-12-20 14:56:56 : 1292857016*/

class GroupsPhoneFixture extends CakeTestFixture {
	var $name = 'GroupsPhone';
	var $table = 'groups_phones';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'group_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'phone_number' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'label' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'privacy' => array('type'=>'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'group_id' => array('column' => 'group_id', 'unique' => 0), 'privacy' => array('column' => 'privacy', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'group_id'  => 1,
		'phone_number'  => '1-317-489-6818',
		'label'  => 'Test',
		'privacy'  => 0
	));
}
?>
