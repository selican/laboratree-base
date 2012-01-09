<?php 
/* SVN FILE: $Id$ */
/* GroupsAward Fixture generated on: 2010-12-20 14:56:42 : 1292857002*/

class GroupsAwardFixture extends CakeTestFixture {
	var $name = 'GroupsAward';
	var $table = 'groups_awards';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'group_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'label' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'award' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'privacy' => array('type'=>'boolean', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'group_id' => array('column' => 'group_id', 'unique' => 0), 'privacy' => array('column' => 'privacy', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'group_id'  => 1,
		'label'  => 'Test',
		'award'  => 'Test',
		'privacy'  => 0
	));
}
?>
