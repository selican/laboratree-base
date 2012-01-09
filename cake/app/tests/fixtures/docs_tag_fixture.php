<?php 
/* SVN FILE: $Id$ */
/* DocsTag Fixture generated on: 2010-12-20 14:54:50 : 1292856890*/

class DocsTagFixture extends CakeTestFixture {
	var $name = 'DocsTag';
	var $table = 'docs_tags';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'doc_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'tag_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'doc_id' => array('column' => 'doc_id', 'unique' => 0), 'tag_id' => array('column' => 'tag_id', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'doc_id'  => 1,
		'tag_id'  => 1
	));
}
?>