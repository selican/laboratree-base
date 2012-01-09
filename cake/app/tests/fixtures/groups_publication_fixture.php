<?php 
/* SVN FILE: $Id$ */
/* GroupsPublication Fixture generated on: 2010-12-20 14:57:10 : 1292857030*/

class GroupsPublicationFixture extends CakeTestFixture {
	var $name = 'GroupsPublication';
	var $table = 'groups_publications';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'group_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'title' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'authors' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'journal' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'year' => array('type'=>'integer', 'null' => true, 'default' => NULL, 'length' => 4),
		'pubmed_id' => array('type'=>'integer', 'null' => true, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'link' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'privacy' => array('type'=>'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'group_id' => array('column' => 'group_id', 'unique' => 0), 'pubmed_id' => array('column' => 'pubmed_id', 'unique' => 0), 'privacy' => array('column' => 'privacy', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'group_id'  => 1,
		'title'  => 'Test',
		'authors'  => 'Test User',
		'journal'  => 'Test',
		'year'  => 2010,
		'pubmed_id'  => '20052762',
		'link'  => 'http://testgrppub.example.com',
		'privacy'  => 0
	));
}
?>
