<?php 
/* SVN FILE: $Id$ */
/* UsersEducation Fixture generated on: 2010-12-20 15:01:29 : 1292857289*/

class UsersEducationFixture extends CakeTestFixture {
	var $name = 'UsersEducation';
	var $table = 'users_educations';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'user_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'label' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'institution' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'years' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'degree' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'privacy' => array('type'=>'boolean', 'null' => false, 'default' => '0'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'user_id' => array('column' => 'user_id', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'user_id'  => 1,
		'label'  => 'Test',
		'institution'  => 'Test',
		'years'  => '2010',
		'degree'  => 'Test',
		'privacy'  => 0
	));
}
?>
