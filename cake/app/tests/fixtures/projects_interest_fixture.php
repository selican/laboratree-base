<?php 
/* SVN FILE: $Id$ */
/* ProjectsInterest Fixture generated on: 2010-12-20 14:59:24 : 1292857164*/

class ProjectsInterestFixture extends CakeTestFixture {
	var $name = 'ProjectsInterest';
	var $table = 'projects_interests';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'project_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'interest_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'privacy' => array('type'=>'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'project_id' => array('column' => 'project_id', 'unique' => 0), 'interest_id' => array('column' => 'interest_id', 'unique' => 0), 'privacy' => array('column' => 'privacy', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'project_id'  => 1,
		'interest_id'  => 1,
		'privacy'  => 0
	));
}
?>
