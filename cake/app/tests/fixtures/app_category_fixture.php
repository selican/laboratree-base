<?php 
/* SVN FILE: $Id$ */
/* AppCategory Fixture generated on: 2010-12-20 14:53:11 : 1292856791*/

class AppCategoryFixture extends CakeTestFixture {
	var $name = 'AppCategory';
	var $table = 'app_categories';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'app_id' => array('type'=>'integer', 'null' => false, 'default' => NULL),
		'category' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);
	var $records = array(array(
		'id'  => 1,
		'app_id'  => 1,
		'category'  => 'Scientific Analysis'
	));
}
?>
