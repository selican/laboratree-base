<?php 
/* SVN FILE: $Id$ */
/* ProjectsSetting Fixture generated on: 2010-12-20 14:59:31 : 1292857171*/

class ProjectsSettingFixture extends CakeTestFixture {
	var $name = 'ProjectsSetting';
	var $table = 'projects_settings';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'project_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'setting_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'value' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'project_id' => array('column' => 'project_id', 'unique' => 0), 'setting_id' => array('column' => 'setting_id', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'project_id'  => 1,
		'setting_id'  => 1,
		'value'  => 'true'
	));
}
?>
