<?php 
/* SVN FILE: $Id$ */
/* Url Fixture generated on: 2010-12-20 15:00:42 : 1292857242*/

class UrlFixture extends CakeTestFixture {
	var $name = 'Url';
	var $table = 'urls';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'table_type' => array('type'=>'string', 'null' => false, 'default' => 'user'),
		'table_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'link' => array('type'=>'string', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'label' => array('type'=>'string', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'description' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'privacy' => array('type'=>'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'table_type' => array('column' => 'table_type', 'unique' => 0), 'table_id' => array('column' => 'table_id', 'unique' => 0), 'link' => array('column' => 'link', 'unique' => 0), 'label' => array('column' => 'label', 'unique' => 0), 'privacy' => array('column' => 'privacy', 'unique' => 0))
	);
	var $records = array(
		array(
			'id'  => 1,
			'table_type' => 'user',
			'table_id'  => 1,
			'link'  => 'http://example.com',
			'label'  => 'Test',
			'description'  => 'Test',
			'privacy'  => 0
		),
		array(
			'id'  => 2,
			'table_type' => 'group',
			'table_id'  => 1,
			'link'  => 'http://example.com',
			'label'  => 'Test',
			'description'  => 'Test',
			'privacy'  => 0
		),
		array(
			'id'  => 3,
			'table_type' => 'project',
			'table_id'  => 1,
			'link'  => 'http://example.com',
			'label'  => 'Test',
			'description'  => 'Test',
			'privacy'  => 0
		),
		array(
			'id'  => 4,
			'table_type' => 'group',
			'table_id'  => 2,
			'link'  => 'http://example.com',
			'label'  => 'Test',
			'description'  => 'Test',
			'privacy'  => 0
		),

	);
}
?>
