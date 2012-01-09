<?php 
/* SVN FILE: $Id$ */
/* SiteRole Fixture generated on: 2010-12-20 14:59:52 : 1292857192*/

class SiteRoleFixture extends CakeTestFixture {
	var $name = 'SiteRole';
	var $table = 'site_roles';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'type' => array('type'=>'string', 'null' => false, 'default' => 'group'),
		'rank' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 2, 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'type' => array('column' => 'type', 'unique' => 0), 'rank' => array('column' => 'rank', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'name'  => 'group.manager',
		'type' => 'group',
		'rank'  => 0
	),array(
		'id' => 2,
		'name' => 'group.member',
		'type' => 'group',
		'rank' => 1,
	),array(
		'id' => 3,
		'name' => 'user.manager',
		'type' => 'user',
		'rank' => 0,
	),array(
		'id' => 4,
		'name' => 'user.colleague',
		'type' => 'user',
		'rank' => 1,
	),array(
		'id' => 5,
		'name' => 'project.manager',
		'type' => 'project',
		'rank' => 0,
	),array(
		'id' => 6,
		'name' => 'project.member',
		'type' => 'project',
		'rank' => 1,
	));
}
?>
