<?php 
/* SVN FILE: $Id$ */
/* ProjectsUrl Fixture generated on: 2010-12-20 14:59:37 : 1292857177*/

class ProjectsUrlFixture extends CakeTestFixture {
	var $name = 'ProjectsUrl';
	var $table = 'projects_urls';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'project_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'link' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'label' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'privacy' => array('type'=>'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'project_id' => array('column' => 'project_id', 'unique' => 0), 'privacy' => array('column' => 'privacy', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'project_id'  => 1,
		'link'  => 'http://example.com',
		'label'  => 'Test',
		'privacy'  => 0
	));
}
?>
