<?php 
/* SVN FILE: $Id$ */
/* GroupsProject Fixture generated on: 2010-12-20 14:57:05 : 1292857025*/

class GroupsProjectsFixture extends CakeTestFixture {
	var $name = 'GroupsProjects';
	var $table = 'groups_projects';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'group_id' => array('type'=>'integer', 'null' => false, 'default' => '0', 'length' => 10, 'key' => 'index'),
		'project_id' => array('type'=>'integer', 'null' => false, 'default' => '0', 'length' => 10, 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'project_id' => array('column' => 'project_id', 'unique' => 0), 'group_id' => array('column' => 'group_id', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'group_id'  => 1,
		'project_id'  => 1
	));
}
?>
