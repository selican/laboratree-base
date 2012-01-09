<?php 
/* SVN FILE: $Id$ */
/* UsersPreference Fixture generated on: 2010-12-20 15:01:56 : 1292857316*/

class UsersPreferenceFixture extends CakeTestFixture {
	var $name = 'UsersPreference';
	var $table = 'users_preferences';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'table_type' => array('type'=>'string', 'null' => false, 'default' => 'user'),
		'table_id' => array('type'=>'integer', 'null' => true, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'user_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'preference_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'value' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'user_id' => array('column' => 'user_id', 'unique' => 0), 'preference_id' => array('column' => 'preference_id', 'unique' => 0), 'table_type' => array('column' => 'table_type', 'unique' => 0), 'table_id' => array('column' => 'table_id', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'table_type' => 'user',
		'table_id'  => 1,
		'user_id'  => 1,
		'preference_id'  => 5,
		'value'  => 'true'
	));
}
?>
