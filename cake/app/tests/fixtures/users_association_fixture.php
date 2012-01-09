<?php 
/* SVN FILE: $Id$ */
/* UsersAssociation Fixture generated on: 2010-12-20 15:01:12 : 1292857272*/

class UsersAssociationFixture extends CakeTestFixture {
	var $name = 'UsersAssociation';
	var $table = 'users_associations';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'user_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'label' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'association' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'role' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'privacy' => array('type'=>'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'user_id' => array('column' => 'user_id', 'unique' => 0), 'privacy' => array('column' => 'privacy', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'user_id'  => 1,
		'label'  => 'Test',
		'association'  => 'Test',
		'role'  => 'Test',
		'privacy'  => 1
	));
}
?>
