<?php 
/* SVN FILE: $Id$ */
/* DocsTypeDatum Fixture generated on: 2010-12-20 14:55:39 : 1292856939*/

class DocsTypeDataFixture extends CakeTestFixture {
	var $name = 'DocsTypeData';
	var $table = 'docs_type_data';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'doc_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'type_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'row_id' => array('type'=>'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'field_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'value' => array('type'=>'text', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'doc_id' => array('column' => 'doc_id', 'unique' => 0), 'type_id' => array('column' => 'type_id', 'unique' => 0), 'field_id' => array('column' => 'field_id', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'doc_id'  => 1,
		'type_id'  => 1,
		'row_id'  => 1,
		'field_id'  => 1,
		'value'  => 'Test'
	));
}
?>
