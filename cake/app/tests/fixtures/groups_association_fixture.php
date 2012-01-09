<?php 
/* SVN FILE: $Id$ */
/* GroupsAssociation Fixture generated on: 2010-12-20 14:56:36 : 1292856996*/

class GroupsAssociationFixture extends CakeTestFixture {
	var $name = 'GroupsAssociation';
	var $table = 'groups_associations';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'group_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'label' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'association' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'role' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'privacy' => array('type'=>'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'group_id' => array('column' => 'group_id', 'unique' => 0), 'privacy' => array('column' => 'privacy', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'group_id'  => 1,
		'label'  => 'Test',
		'association'  => 'Test',
		'role'  => 'Test',
		'privacy'  => 0
	));
}
?>
