<?php 
/* SVN FILE: $Id$ */
/* DocsTypeField Fixture generated on: 2010-12-20 14:55:44 : 1292856944*/

class DocsTypeFieldFixture extends CakeTestFixture {
	var $name = 'DocsTypeField';
	var $table = 'docs_type_fields';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'type_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'name' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'type' => array('type'=>'string', 'null' => false, 'default' => 'number'),
		'required' => array('type'=>'boolean', 'null' => false, 'default' => NULL),
		'options' => array('type'=>'text', 'null' => true, 'default' => NULL),
		'display' => array('type'=>'boolean', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'type_id' => array('column' => 'type_id', 'unique' => 0))
	);
	var $records = array(
		array(
			'id'  => 1,
			'type_id'  => 1,
			'name'  => 'Title',
			'type' => 'text',
			'required'  => 1,
			'options'  => null,
			'display'  => 1
		),
		array(
			'id' => 2,
			'type_id' => 3,
			'name' => 'Title',
			'type' => 'text',
			'required' => 1,
			'options' => null,
			'display' => 1,
		),
		array(
			'id' => 3,
			'type_id' => 4,
			'name' => 'Title',
			'type' => 'text',
			'required' => 1,
			'options' => null,
			'display' => 1,
		),
		array(
			'id' => 4,
			'type_id' => 5,
			'name' => 'Title',
			'type' => 'text',
			'required' => 1,
			'options' => null,
			'display' => 1,
		),
		array(
			'id' => 5,
			'type_id' => 2,
			'name' => 'Title',
			'type' => 'text',
			'required' => 1,
			'options' => null,
			'display' => 1,
		),
		array(
			'id' => 6,
			'type_id' => 9,
			'name' => 'Title',
			'type' => 'text',
			'required' => 1,
			'options' => null,
			'display' => 1,
		),
		array(
			'id' => 7,
			'type_id' => 10,
			'name' => 'Title',
			'type' => 'text',
			'required' => 1,
			'options' => null,
			'display' => 1,
		),
		array(
			'id' => 8,
			'type_id' => 11,
			'name' => 'Title',
			'type' => 'text',
			'required' => 1,
			'options' => null,
			'display' => 1,
		),
		array(
			'id' => 9,
			'type_id' => 8,
			'name' => 'Title',
			'type' => 'text',
			'required' => 1,
			'options' => null,
			'display' => 1,
		),
		array(
			'id' => 10,
			'type_id' => 6,
			'name' => 'Title',
			'type' => 'text',
			'required' => 1,
			'options' => null,
			'display' => 1,
		),
		array(
			'id' => 11,
			'type_id' => 7,
			'name' => 'Title',
			'type' => 'text',
			'required' => 1,
			'options' => null,
			'display' => 1,
		),
	);
}
?>
