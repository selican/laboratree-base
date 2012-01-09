<?php 
/* SVN FILE: $Id$ */
/* WorkflowStep Fixture generated on: 2011-08-31 13:30:51 : 1314797451*/

class WorkflowStepFixture extends CakeTestFixture {
	var $name = 'WorkflowStep';
	var $table = 'workflow_steps';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'workflow_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'type' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'title' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'content' => array('type'=>'text', 'null' => true, 'default' => NULL),
		'url' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'lft' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'rght' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'parent_id' => array('type'=>'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'button' => array('type'=>'boolean', 'null' => false, 'default' => '0'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'workflow_id' => array('column' => 'workflow_id', 'unique' => 0), 'lft' => array('column' => 'lft', 'unique' => 0), 'rght' => array('column' => 'rght', 'unique' => 0), 'parent_id' => array('column' => 'parent_id', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'workflow_id'  => 1,
		'type' => '',
		'title'  => 'Test Step',
		'content'  => 'Test Content',
		'url'  => '',
		'lft'  => 1,
		'rght'  => 2,
		'parent_id'  => null,
		'button'  => 0
	));
}
?>
