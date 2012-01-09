<?php 
/* SVN FILE: $Id$ */
/* DocsType Fixture generated on: 2010-12-20 14:56:03 : 1292856963*/

class DocsTypeFixture extends CakeTestFixture {
	var $name = 'DocsType';
	var $table = 'docs_types';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'doc_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'type_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'doc_id' => array('column' => 'doc_id', 'unique' => 0), 'type_id' => array('column' => 'type_id', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'doc_id'  => 1,
		'type_id'  => 1
	));
}
?>