<?php 
/* SVN FILE: $Id$ */
/* UsersPublication Fixture generated on: 2010-12-20 15:02:05 : 1292857325*/

class UsersPublicationFixture extends CakeTestFixture {
	var $name = 'UsersPublication';
	var $table = 'users_publications';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'user_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'title' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'authors' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'journal' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'year' => array('type'=>'integer', 'null' => true, 'default' => NULL, 'length' => 4),
		'page_start' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'page_end' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'volume' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'num_pages' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'pubmed_id' => array('type'=>'integer', 'null' => true, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'link' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'privacy' => array('type'=>'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'user_id' => array('column' => 'user_id', 'unique' => 0), 'pubmed_id' => array('column' => 'pubmed_id', 'unique' => 0), 'privacy' => array('column' => 'privacy', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'user_id'  => 1,
		'title'  => 'Test',
		'authors'  => 'Test User',
		'journal'  => 'Test',
		'year'  => 1999,
		'page_start'  => '1',
		'page_end'  => '1',
		'volume'  => '1',
		'num_pages'  => '1',
		'pubmed_id'  => 20052762,
		'link'  => 'http://example.com',
		'privacy'  => 0
	));
}
?>
