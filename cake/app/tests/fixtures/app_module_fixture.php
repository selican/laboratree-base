<?php 
/* SVN FILE: $Id$ */
/* AppModule Fixture generated on: 2010-12-20 14:53:28 : 1292856808*/

class AppModuleFixture extends CakeTestFixture {
	var $name = 'AppModule';
	var $table = 'app_modules';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'app_id' => array('type'=>'integer', 'null' => false, 'default' => '0', 'length' => 10, 'key' => 'unique'),
		'table_type' => array('type'=>'string', 'null' => false, 'default' => 'user'),
		'table_id' => array('type'=>'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'group_id' => array('column' => 'app_id', 'unique' => 1))
	);
	var $records = array(array(
		'id'  => 1,
		'app_id'  => 1,
		'table_type' => 'user',
		'table_id'  => 1
	));
}
?>
