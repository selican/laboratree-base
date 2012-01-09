<?php 
/* SVN FILE: $Id$ */
/* Preference Fixture generated on: 2010-12-20 14:58:57 : 1292857137*/

class PreferenceFixture extends CakeTestFixture {
	var $name = 'Preference';
	var $table = 'preferences';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'name' => array('type'=>'string', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'title' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'description' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'type' => array('type'=>'string', 'null' => false, 'default' => 'root'),
		'field' => array('type'=>'string', 'null' => true, 'default' => 'number'),
		'field_options' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'default' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'parent_id' => array('type'=>'integer', 'null' => true, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'lft' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'rght' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'type' => array('column' => 'type', 'unique' => 0), 'name' => array('column' => 'name', 'unique' => 0), 'parent_id' => array('column' => 'parent_id', 'unique' => 0), 'lft' => array('column' => 'lft', 'unique' => 0), 'rght' => array('column' => 'rght', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'name'  => 'Preferences Root',
		'title'  => null,
		'description'  => '',
		'type' => 'root',
		'field' => NULL,
		'field_options'  => NULL,
		'default' => null,
		'parent_id'  => NULL,
		'lft'  => 1,
		'rght'  => 296
	),array(
		'id'  => 2,
		'name'  => 'group',
		'title'  => 'Group',
		'description'  => 'Groups Preferences',
		'type' => 'section',
		'field' => NULL,
		'field_options'  => NULL,
		'default' => null,
		'parent_id'  => 1,
		'lft'  => 2,
		'rght'  => 155
	),array(
		'id'  => 3,
		'name'  => 'documents',
		'title'  => 'Document',
		'description'  => 'Documents Preferences',
		'type' => 'controller',
		'field' => NULL,
		'field_options'  => NULL,
		'default' => null,
		'parent_id'  => 2,
		'lft'  => 3,
		'rght'  => 28
	),array(
		'id'  => 4,
		'name'  => 'add',
		'title'  => 'Add',
		'description'  => '',
		'type' => 'action',
		'field' => NULL,
		'field_options'  => NULL,
		'default' => null,
		'parent_id'  => 3,
		'lft'  => 4,
		'rght'  => 7
	),array(
		'id'  => 5,
		'name'  => 'email',
		'title'  => 'Email',
		'description'  => '',
		'type' => 'function',
		'field' => 'option',
		'field_options'  => 'Always, Digest, Never',
		'default' => 'Always',
		'parent_id'  => 4,
		'lft'  => 5,
		'rght'  => 6
	),array(
                'id'  => 6,
                'name'  => 'edit',
                'title'  => 'Edit',
                'description'  => '',
                'type' => 'action',
                'field' => null,
                'field_options'  => null,
                'default' => null,
                'parent_id'  => 3,
                'lft'  => 8,
                'rght'  => 11
        ),array(
                'id'  => 7,
                'name'  => 'email',
                'title'  => 'Email',
                'description'  => '',
                'type' => 'function',
                'field' => 'option',
                'field_options'  => 'Always, Digest, Never',
                'default' => 'Always',
                'parent_id'  => 6,
                'lft'  => 9,
                'rght'  => 10
        ),array(
                'id'  => 8,
                'name'  => 'delete',
                'title'  => 'Delete',
                'description'  => '',
                'type' => 'action',
                'field' => NULL,
                'field_options'  => NULL,
                'default' => null,
                'parent_id'  => 3,
                'lft'  => 12,
                'rght'  => 15
        ),array(
                'id'  => 9,
                'name'  => 'email',
                'title'  => 'Email',
                'description'  => '',
                'type' => 'function',
                'field' => 'option',
                'field_options'  => 'Always, Digest, Never',
                'default' => 'Always',
                'parent_id'  => 8,
                'lft'  => 13,
                'rght'  => 14
        ),array(
                'id'  => 10,
                'name'  => 'checkout',
                'title'  => 'Check-out',
                'description'  => '',
                'type' => 'action',
                'field' => null,
                'field_options'  => null,
                'default' => null,
                'parent_id'  => 3,
                'lft'  => 16,
                'rght'  => 19
        ),array(
                'id'  => 11,
                'name'  => 'email',
                'title'  => 'Email',
                'description'  => '',
                'type' => 'function',
                'field' => 'option',
                'field_options'  => 'Always, Digest, Never',
                'default' => 'Always',
                'parent_id'  => 10,
                'lft'  => 17,
                'rght'  => 18
        ),array(
                'id'  => 12,
                'name'  => 'cancelcheckout',
                'title'  => 'Cancel Check-out',
                'description'  => '',
                'type' => 'action',
                'field' => NULL,
                'field_options'  => NULL,
                'default' => null,
                'parent_id'  => 3,
                'lft'  => 20,
                'rght'  => 23
        ),array(
                'id'  => 13,
                'name'  => 'email',
                'title'  => 'Email',
                'description'  => '',
                'type' => 'function',
                'field' => 'option',
                'field_options'  => 'Always, Digest, Never',
                'default' => 'Always',
                'parent_id'  => 12,
                'lft'  => 21,
                'rght'  => 22
        ),array(
                'id'  => 14,
                'name'  => 'checkin',
                'title'  => 'Check-in',
                'description'  => '',
                'type' => 'action',
                'field' => null,
                'field_options'  => null,
                'default' => null,
                'parent_id'  => 3,
                'lft'  => 24,
                'rght'  => 27
        ),array(
                'id'  => 15,
                'name'  => 'email',
                'title'  => 'Email',
                'description'  => '',
                'type' => 'function',
                'field' => 'option',
                'field_options'  => 'Always, Digest, Never',
                'default' => 'Always',
                'parent_id'  => 14,
                'lft'  => 25,
                'rght'  => 26
        ),array(
                'id'  => 16,
                'name'  => 'discussions',
                'title'  => 'Discussion',
                'description'  => '',
                'type' => 'controller',
                'field' => null,
                'field_options'  => null,
                'default' => null,
                'parent_id'  => 2,
                'lft'  => 29,
                'rght'  => 58
        ),array(
                'id'  => 17,
                'name'  => 'categoryadd',
                'title'  => 'Category Add',
                'description'  => '',
                'type' => 'action',
                'field' => null,
                'field_options'  => null,
                'default' => null,
                'parent_id'  => 16,
                'lft'  => 30,
                'rght'  => 33
        ),array(
                'id'  => 18,
                'name'  => 'email',
                'title'  => 'Email',
                'description'  => '',
                'type' => 'function',
                'field' => 'option',
                'field_options'  => 'Always, Digest, Never',
                'default' => 'Always',
                'parent_id'  => 17,
                'lft'  => 31,
                'rght'  => 32
        ),array(
                'id'  => 19,
                'name'  => 'categoryedit',
                'title'  => 'Category Edit',
                'description'  => '',
                'type' => 'action',
                'field' => null,
                'field_options'  => null,
                'default' => null,
                'parent_id'  => 16,
                'lft'  => 34,
                'rght'  => 47
        ),array(
                'id'  => 20,
                'name'  => 'email',
                'title'  => 'Email',
                'description'  => '',
                'type' => 'function',
                'field' => 'option',
                'field_options'  => 'Always, Digest, Never',
                'default' => 'Always',
                'parent_id'  => 19,
                'lft'  => 35,
                'rght'  => 36
        ),array(
                'id'  => 21,
                'name'  => 'categorydelete',
                'title'  => 'Category Delete',
                'description'  => '',
                'type' => 'action',
                'field' => null,
                'field_options'  => null,
                'default' => null,
                'parent_id'  => 16,
                'lft'  => 28,
                'rght'  => 41
        ),array(
                'id'  => 22,
                'name'  => 'email',
                'title'  => 'Email',
                'description'  => '',
                'type' => 'function',
                'field' => 'option',
                'field_options'  => 'Always, Digest, Never',
                'default' => 'Always',
                'parent_id'  => 21,
                'lft'  => 39,
                'rght'  => 40
        ),array(
                'id'  => 23,
                'name'  => 'topicadd',
                'title'  => 'Topic Add',
                'description'  => '',
                'type' => 'action',
                'field' => null,
                'field_options'  => null,
                'default' => null,
                'parent_id'  => 16,
                'lft'  => 42,
                'rght'  => 45
        ),array(
                'id'  => 24,
                'name'  => 'email',
                'title'  => 'Email',
                'description'  => '',
                'type' => 'function',
                'field' => 'option',
                'field_options'  => 'Always, Digest, Never',
                'default' => 'Always',
                'parent_id'  => 23,
                'lft'  => 43,
                'rght'  => 44
        ),array(
                'id'  => 25,
                'name'  => 'topicedit',
                'title'  => 'Topic Edit',
                'description'  => '',
                'type' => 'action',
                'field' => null,
                'field_options'  => null,
                'default' => null,
                'parent_id'  => 16,
                'lft'  => 46,
                'rght'  => 49
        ),array(
                'id'  => 26,
                'name'  => 'email',
                'title'  => 'Email',
                'description'  => '',
                'type' => 'function',
                'field' => 'option',
                'field_options'  => 'Always, Digest, Never',
                'default' => 'Always',
                'parent_id'  => 25,
                'lft'  => 47,
                'rght'  => 48
        ),array(
                'id'  => 27,
                'name'  => 'topicdelete',
                'title'  => 'Topic Delete',
                'description'  => '',
                'type' => 'action',
                'field' => null,
                'field_options'  => null,
                'default' => null,
                'parent_id'  => 16,
                'lft'  => 50,
                'rght'  => 53

        ),array(
                'id'  => 28,
                'name'  => 'email',
                'title'  => 'Email',
                'description'  => '',
                'type' => 'function',
                'field' => 'option',
                'field_options'  => 'Always, Digest, Never',
                'default' => 'Always',
                'parent_id'  => 27,
                'lft'  => 51,
                'rght'  => 52
        ),array(
                'id'  => 29,
                'name'  => 'topicreply',
                'title'  => 'Topic Reply',
                'description'  => '',
                'type' => 'action',
                'field' => null,
                'field_options'  => null,
                'default' => null,
                'parent_id'  => 16,
                'lft'  => 54,
                'rght'  => 57
        ),array(
                'id'  => 30,
                'name'  => 'email',
                'title'  => 'Email',
                'description'  => '',
                'type' => 'function',
                'field' => 'option',
                'field_options'  => 'Always, Digest, Never',
                'default' => 'Always',
                'parent_id'  => 29,
                'lft'  => 55,
                'rght'  => 56
	),array(
                'id'  => 31,
                'name'  => 'project',
                'title'  => 'Project',
                'description'  => '',
                'type' => 'section',
                'field' => null,
                'field_options'  => null,
                'default' => null,
                'parent_id'  => 1,
                'lft'  => 156,
                'rght'  => 295
       ),array(
                'id'  => 32,
                'name'  => 'documents',
                'title'  => 'Documents',
                'description'  => '',
                'type' => 'section',
                'field' => null,
                'field_options'  => null,
                'default' => null,
                'parent_id'  => 31,
                'lft'  => 157,
                'rght'  => 182
        ),array(
                'id'  => 33,
                'name'  => 'add',
                'title'  => 'Add',
                'description'  => '',
                'type' => 'action',
                'field' => null,
                'field_options'  => null,
                'default' => null,
                'parent_id'  => 32,
                'lft'  => 158,
                'rght'  => 161
        ),array(
                'id'  => 34,
                'name'  => 'email',
                'title'  => 'Email',
                'description'  => '',
                'type' => 'function',
                'field' => 'option',
                'field_options'  => 'Always, Digest, Never',
                'default' => 'Always',
                'parent_id'  => 33,
                'lft'  => 159,
                'rght'  => 160

	)


	);
}
?>
