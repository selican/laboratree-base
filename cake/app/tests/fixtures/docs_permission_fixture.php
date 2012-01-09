<?php 
/* SVN FILE: $Id$ */
/* DocsPermission Fixture generated on: 2010-12-20 14:54:46 : 1292856886*/

class DocsPermissionFixture extends CakeTestFixture {
	var $name = 'DocsPermission';
	var $table = 'docs_permissions';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'doc_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'role_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'create' => array('type'=>'boolean', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'read' => array('type'=>'boolean', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'update' => array('type'=>'boolean', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'delete' => array('type'=>'boolean', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'cico' => array('type'=>'boolean', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'download' => array('type'=>'boolean', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'inherit' => array('type'=>'boolean', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'doc_id' => array('column' => 'doc_id', 'unique' => 0), 'role_id' => array('column' => 'role_id', 'unique' => 0), 'create' => array('column' => 'create', 'unique' => 0), 'read' => array('column' => 'read', 'unique' => 0), 'update' => array('column' => 'update', 'unique' => 0), 'delete' => array('column' => 'delete', 'unique' => 0), 'cico' => array('column' => 'cico', 'unique' => 0), 'download' => array('column' => 'download', 'unique' => 0), 'inherit' => array('column' => 'inherit', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'doc_id'  => 1,
		'role_id'  => 1,
		'create'  => 1,
		'read'  => 1,
		'update'  => 1,
		'delete'  => 1,
		'cico'  => 1,
		'download'  => 1,
		'inherit'  => 1
	));
}
?>