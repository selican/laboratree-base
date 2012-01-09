<?php 
/* SVN FILE: $Id$ */
/* GroupsInterest Fixture generated on: 2010-12-20 14:56:50 : 1292857010*/

class GroupsInterestFixture extends CakeTestFixture {
	var $name = 'GroupsInterest';
	var $table = 'groups_interests';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'group_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'interest_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'privacy' => array('type'=>'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'group_id' => array('column' => 'group_id', 'unique' => 0), 'interest_id' => array('column' => 'interest_id', 'unique' => 0), 'privacy' => array('column' => 'privacy', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'group_id'  => 1,
		'interest_id'  => 1,
		'privacy'  => 0
	));
}
?>
