<?php 
/* SVN FILE: $Id$ */
/* Tag Fixture generated on: 2010-12-20 15:00:25 : 1292857225*/

class TagFixture extends CakeTestFixture {
	var $name = 'Tag';
	var $table = 'tags';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'keyword' => array('type'=>'string', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'keyword' => array('column' => 'keyword', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'name'  => 'Test Test',
		'keyword'  => 'testtest'
	),
	array(
		'id' => 2,
		'name' => 'Unique1',
		'keyword' => 'unique1',
	));
}
?>
