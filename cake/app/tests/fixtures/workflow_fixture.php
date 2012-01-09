<?php 
/* SVN FILE: $Id$ */
/* Workflow Fixture generated on: 2011-08-31 13:31:06 : 1314797466*/

class WorkflowFixture extends CakeTestFixture {
	var $name = 'Workflow';
	var $table = 'workflows';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'title' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'description' => array('type'=>'text', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);
	var $records = array(array(
		'id'  => 1,
		'title'  => 'Test',
		'description'  => 'Test Workflow',
	));
}
?>
