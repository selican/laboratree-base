<?php
/* SVN FILE: $Id$ */
/* Permission Fixture generated on: 2011-08-31 16:33:29 : 1314808409*/

class PermFixture extends CakeTestFixture {
        var $name = 'Perm';

        var $table = 'permissions';
        var $fields = array(
 
'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
'root' => array('type'=>'string', 'null' => false, 'default' => 'something'),
'name' => array('type'=>'string', 'null' => false, 'default' => NULL, 'key' => 'index'),
'title'  => array('type'=>'string', 'null' => false, 'default' => NULL),
'mask' => array('type'=>'string', 'null' => false, 'default' => NULL),
'created' => array('type'=>'string', 'null' => false, 'default' => NULL),
'modified' => array('type'=>'string', 'null' => false, 'default' => NULL),
'lft' => array('type'=>'string', 'null' => false, 'default' => NULL),
'rght' => array('type'=>'string', 'null' => false, 'default' => NULL),
'parent_id' => array('type'=>'string', 'null' => false, 'default' => NULL),
);

        var $records = array(array(
                'id'  => 1,
		'root' => null,
                'name'  => 'root',
                'title' => 'Root',
                'mask'  => NULL,
                'created'  => '2011-08-31 16:33:29',
                'modified'  => '2011-08-31 16:33:29',
                'lft'  => 1,
                'rght'  => 8,
                'parent_id'  => NULL,
        ),array(
                'id' => 2,
                'name' => 'discussion',
                'title' => 'Discussions',
                'mask' => NULL,
                'created'  => '2011-08-31 16:33:29',
                'modified'  => '2011-08-31 16:33:29',
                'lft'  => 2,
                'rght'  => 7,
                'parent_id'  => 1,
        ),array(
                'id' => 3,
                'name' => 'discussion.view',
                'title' => 'View Discussion',
                'mask' => 1,
                'created'  => '2011-08-31 16:33:29',
                'modified'  => '2011-08-31 16:33:29',
                'lft'  => 3,
                'rght'  => 6,
                'parent_id'  => 2,
        ),array(
                'id' => 4,
                'name' => 'discussion.category.add',
                'title' => 'Add Discussion Category',
                'mask' => 2,
                'created'  => '2011-08-31 16:33:29',
                'modified'  => '2011-08-31 16:33:29',
                'lft'  => 4,
                'rght'  => 5,
                'parent_id'  => 2,
        ),array(
                'id' => 5,
                'name' => 'document',
                'title' => 'Documents',
                'mask' => NULL,
                'created'  => '2011-08-31 16:33:29',
                'modified'  => '2011-08-31 16:33:29',
                'lft'  => 2,
                'rght'  => 7,
                'parent_id'  => 1,
        ),array(
                'id' => 6,
                'name' => 'group',
                'title' => 'Groups',
                'mask' => NULL,
                'created'  => '2011-08-31 16:33:29',
                'modified'  => '2011-08-31 16:33:29',
                'lft'  => 2,
                'rght'  => 7,
                'parent_id'  => 1,

        ),array(
                'id' => 7,
                'name' => 'group.permissions',
                'title' => 'View Group Dashboard',
                'mask' => 1,
                'created'  => '2011-08-31 16:33:29',
                'modified'  => '2011-08-31 16:33:29',
                'lft'  => 2,
                'rght'  => 7,
                'parent_id'  => 6,
));
}
?>
