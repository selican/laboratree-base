<?php
/* SVN FILE: $Id$ */
/* Permission Fixture generated on: 2011-08-31 16:33:29 : 1314808409*/

class RolesPermissionsFixture extends CakeTestFixture {
        var $name = 'RolesPermissions';
        
//        var $name = 'roles_permissions';

	var $table = 'roles_permissions';
        var $fields = array(
/*                'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
                'name' => array('type'=>'string', 'null' => false, 'default' => NULL, 'key' => 'index'),
                'title' => array('type'=>'string', 'null' => false, 'default' => NULL),
                'mask' => array('type'=>'integer', 'null' => true, 'default' => NULL),
                'created' => array('type'=>'datetime', 'null' => false, 'default' => NULL),
                'modified' => array('type'=>'datetime', 'null' => false, 'default' => NULL),
                'lft' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
                'rght' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
                'parent_id' => array('type'=>'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
                'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'name' => array('column' => 'name', 'unique' => 0), 'lft' => array('column' => 'lft', 'unique' => 0), 'rght' => array('column' => 'rght', 'unique' => 0), 'parent_id' => array('column' => 'parent_id', 'unique' => 0))
        );
*/
 'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
 'name' => array('type'=>'string', 'null' => false, 'default' => NULL, 'key' => 'index'),
'title'  => array('type'=>'string', 'null' => false, 'default' => NULL),
'mask' => array('type'=>'integer', 'null' => false, 'default' => NULL),
'created' => array('type'=>'string', 'null' => false, 'default' => NULL),
'modified' => array('type'=>'string', 'null' => false, 'default' => NULL),
'lft' => array('type'=>'integer', 'null' => false, 'default' => NULL),
'rght' => array('type'=>'integer', 'null' => false, 'default' => NULL),
'parent_id' => array('type'=>'integer', 'null' => false, 'default' => NULL),
);

        var $records = array(array(
                'id'  => 1,
                'name'  => 'root',
                'title' => 'Root',
                'mask'  => NULL,
                'created'  => '2011-08-31 16:33:29',
                'modified'  => '2011-08-31 16:33:29',
                'lft'  => 1,
                'rght'  => 6,
                'parent_id'  => NULL,
        ),array(
                'id' => 2,
                'name' => 'discussion',
                'title' => 'Discussions',
                'mask' => NULL,
                'created'  => '2011-08-31 16:33:29',
                'modified'  => '2011-08-31 16:33:29',
                'lft'  => 2,
                'rght'  => 5,
                'parent_id'  => 1,
        ),array(
                'id' => 3,
                'name' => 'discussion.view',
                'title' => 'View Discussion',
                'mask' => 1,
                'created'  => '2011-08-31 16:33:29',
                'modified'  => '2011-08-31 16:33:29',
                'lft'  => 3,
                'rght'  => 4,
                'parent_id'  => 2,
        ));
}
?>

