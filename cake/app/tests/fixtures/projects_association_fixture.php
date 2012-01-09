<?php 
/* SVN FILE: $Id$ */
/* ProjectsAssociation Fixture generated on: 2010-12-20 14:59:16 : 1292857156*/

class ProjectsAssociationFixture extends CakeTestFixture {
	var $name = 'ProjectsAssociation';
	var $table = 'projects_associations';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'project_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'label' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'association' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'role' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'privacy' => array('type'=>'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'project_id' => array('column' => 'project_id', 'unique' => 0), 'privacy' => array('column' => 'privacy', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'project_id'  => 1,
		'label'  => 'Test',
		'association'  => 'Test',
		'role'  => 'Test',
		'privacy'  => 0
	));
}
?>
