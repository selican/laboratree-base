<?php 
/* SVN FILE: $Id$ */
/* AppData Fixture generated on: 2010-12-20 14:53:20 : 1292856800*/

class AppDataFixture extends CakeTestFixture {
	var $name = 'AppData';
	var $table = 'app_datas';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'module_id' => array('type'=>'integer', 'null' => false, 'default' => NULL),
		'name' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'value' => array('type'=>'text', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);
	var $records = array(array(
		'id'  => 1,
		'module_id'  => 1,
		'name'  => 'name',
		'value'  => 'Test'
	));
}
?>
