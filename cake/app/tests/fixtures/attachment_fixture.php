<?php 
/* SVN FILE: $Id$ */
/* Attachment Fixture generated on: 2010-12-20 14:53:46 : 1292856826*/

class AttachmentFixture extends CakeTestFixture {
	var $name = 'Attachment';
	var $table = 'attachments';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'message_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'name' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'mimetype' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'filename' => array('type'=>'string', 'null' => false, 'default' => NULL, 'length' => 40),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'message_id' => array('column' => 'message_id', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'message_id'  => 1,
		'name'  => 'first.png',
		'mimetype'  => 'image/png',
		'filename'  => 'AAAA'
	));
}
?>
