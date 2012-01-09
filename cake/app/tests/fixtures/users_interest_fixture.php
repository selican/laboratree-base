<?php 
/* SVN FILE: $Id$ */
/* UsersInterest Fixture generated on: 2010-12-20 15:01:35 : 1292857295*/

class UsersInterestFixture extends CakeTestFixture {
	var $name = 'UsersInterest';
	var $table = 'users_interests';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'user_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'interest_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'user_id' => array('column' => 'user_id', 'unique' => 0), 'interest_id' => array('column' => 'interest_id', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'user_id'  => 1,
		'interest_id'  => 1
	));
}
?>