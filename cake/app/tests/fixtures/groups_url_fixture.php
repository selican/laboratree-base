<?php 
/* SVN FILE: $Id$ */
/* GroupsUrl Fixture generated on: 2010-12-20 14:57:31 : 1292857051*/

class GroupsUrlFixture extends CakeTestFixture {
	var $name = 'GroupsUrl';
	var $table = 'groups_urls';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'group_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'link' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'label' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'privacy' => array('type'=>'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'group_id' => array('column' => 'group_id', 'unique' => 0), 'privacy' => array('column' => 'privacy', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'group_id'  => 1,
		'link'  => 'http://example.com',
		'label'  => 'Test',
		'privacy'  => 0
	));
}
?>
