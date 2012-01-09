<?php 
/* SVN FILE: $Id$ */
/* Interest Fixture generated on: 2010-12-20 14:57:52 : 1292857072*/

class InterestFixture extends CakeTestFixture {
	var $name = 'Interest';
	var $table = 'interests';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'keyword' => array('type'=>'string', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'name' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'keyword' => array('column' => 'keyword', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'keyword'  => 'testtest',
		'name'  => 'Test Test',
	));
}
?>
