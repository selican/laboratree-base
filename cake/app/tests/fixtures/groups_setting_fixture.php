<?php 
/* SVN FILE: $Id$ */
/* GroupsSetting Fixture generated on: 2010-12-20 14:57:16 : 1292857036*/

class GroupsSettingFixture extends CakeTestFixture {
	var $name = 'GroupsSetting';
	var $table = 'groups_settings';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'group_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'setting_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'value' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'group_id' => array('column' => 'group_id', 'unique' => 0), 'setting_id' => array('column' => 'setting_id', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'group_id'  => 1,
		'setting_id'  => 1,
		'value'  => 'Test'
	),
	array(
                'id'  => 2,
                'group_id'  => 2,
                'setting_id'  => 1,
                'value'  => 'Test'
        )
	);
}
?>
