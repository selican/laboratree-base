<?php 
/* SVN FILE: $Id$ */
/* DocsTypeRow Fixture generated on: 2010-12-20 14:55:58 : 1292856958*/

class DocsTypeRowFixture extends CakeTestFixture {
	var $name = 'DocsTypeRow';
	var $table = 'docs_type_rows';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'doc_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'type_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'parent_id' => array('type'=>'integer', 'null' => true, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'lft' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'rght' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'doc_id' => array('column' => 'doc_id', 'unique' => 0), 'type_id' => array('column' => 'type_id', 'unique' => 0), 'parent_id' => array('column' => 'parent_id', 'unique' => 0), 'lft' => array('column' => 'lft', 'unique' => 0), 'rght' => array('column' => 'rght', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'doc_id'  => 1,
		'type_id'  => 1,
		'parent_id'  => NULL,
		'lft'  => 1,
		'rght'  => 2
	));
}
?>
