<?php 
/* SVN FILE: $Id$ */
/* Inbox Fixture generated on: 2010-12-20 14:57:47 : 1292857067*/

class InboxFixture extends CakeTestFixture {
	var $name = 'Inbox';
	var $table = 'inboxes';

	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'sender_id' => array('type'=>'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'index'),
		'receiver_id' => array('type'=>'integer', 'null' => true, 'default' => null, 'length' => 10, 'key' => 'index'),
		'receiver_type' => array('type'=>'string', 'null' => false, 'default' => 'user'),
		'message_id' => array('type'=>'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'index'),
		'template' => array('type'=>'string', 'null' => false, 'default' => null),
		'template_data' => array('type'=>'string', 'null' => true, 'default' => null, 'key' => 'index'),
		'status' => array('type'=>'string', 'null' => false, 'default' => 'unread'),
		'trash' => array('type'=>'boolean', 'null' => false, 'default' => '0'),
		'type' => array('type'=>'string', 'null' => false, 'default' => 'sent'),
		'email' => array('type'=>'string', 'null' => true, 'default' => null),
		'parent_id' => array('type'=>'integer', 'null' => true, 'default' => null, 'length' => 10, 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'sender_id' => array('column' => 'sender_id', 'unique' => 0), 'receiver_id' => array('column' => 'receiver_id', 'unique' => 0), 'receiver_type' => array('column' => 'receiver_type', 'unique' => 0), 'message_id' => array('column' => 'message_id', 'unique' => 0), 'parent_id' => array('column' => 'parent_id', 'unique' => 0), 'status' => array('column' => 'status', 'unique' => 0), 'template_data' => array('column' => 'template_data', 'unique' => 0))
	);
	var $records = array(
		array(
			'id'  => 1,
			'sender_id'  => 1,
			'receiver_id'  => 1,
			'receiver_type' => 'user',
			'message_id'  => 1,
			'template'  => 'user_message',
			'template_data'  => '{"sender":"Test User","sender_id":"1"}',
			'status' => 'unread',
			'trash'  => 0,
			'type' => 'sent',
			'email'  => null,
			'parent_id'  => null
		),
		array(
			'id'  => 2,
			'sender_id'  => 1,
			'receiver_id'  => 1,
			'receiver_type' => 'user',
			'message_id'  => 1,
			'template'  => 'user_message',
			'template_data'  => '{"sender":"Test User","sender_id":"1"}',
			'status' => 'unread',
			'trash'  => 0,
			'type' => 'received',
			'email'  => null,
			'parent_id'  => null
		),
		array(
			'id'  => 3,
			'sender_id'  => 2,
			'receiver_id'  => 1,
			'receiver_type' => 'user',
			'message_id'  => 2,
			'template'  => 'group_invite',
			'template_data'  => '{"sender":"Another User","sender_id":"2","group":"Public Test Group","group_id":"2"}',
			'status' => 'unread',
			'trash'  => 0,
			'type' => 'received',
			'email'  => null,
			'parent_id'  => null
		),
		array(
			'id'  => 4,
			'sender_id'  => 1,
			'receiver_id'  => 1,
			'receiver_type' => 'user',
			'message_id'  => 2,
			'template'  => 'group_invite',
			'template_data'  => '{"sender":"Test User","sender_id":"1","group":"Test Group","group_id":"1"}',
			'status' => 'unread',
			'trash'  => 0,
			'type' => 'received',
			'email'  => null,
			'parent_id'  => null
		),
		array(
			'id'  => 5,
			'sender_id'  => 3,
			'receiver_id'  => 1,
			'receiver_type' => 'user',
			'message_id'  => 2,
			'template'  => 'group_request',
			'template_data'  => '{"sender":"Third User","sender_id":"3","group":"Test Group","group_id":"1"}',
			'status' => 'unread',
			'trash'  => 0,
			'type' => 'received',
			'email'  => null,
			'parent_id'  => null
		),
		array(
			'id'  => 6,
			'sender_id'  => 1,
			'receiver_id'  => 1,
			'receiver_type' => 'user',
			'message_id'  => 2,
			'template'  => 'group_request',
			'template_data'  => '{"sender":"Test User","sender_id":"1","group":"Test Group","group_id":"1"}',
			'status' => 'unread',
			'trash'  => 0,
			'type' => 'received',
			'email'  => null,
			'parent_id'  => null
		),
		array(
			'id'  => 7,
			'sender_id'  => 2,
			'receiver_id'  => 1,
			'receiver_type' => 'user',
			'message_id'  => 2,
			'template'  => 'user_message',
			'template_data'  => '{"sender":"Another User","sender_id":"2"}',
			'status' => 'unread',
			'trash'  => 0,
			'type' => 'received',
			'email'  => null,
			'parent_id'  => null
		),
		array(
			'id'  => 8,
			'sender_id'  => 1,
			'receiver_id'  => 2,
			'receiver_type' => 'user',
			'message_id'  => 3,
			'template'  => 'user_message',
			'template_data'  => '{"sender":"Test User","sender_id":"1"}',
			'status' => 'read',
			'trash'  => 0,
			'type' => 'received',
			'email'  => null,
			'parent_id'  => null
		),
		array(
			'id'  => 9,
			'sender_id'  => 2,
			'receiver_id'  => 1,
			'receiver_type' => 'user',
			'message_id'  => 4,
			'template'  => 'user_message',
			'template_data'  => '{"sender":"Another User","sender_id":"2"}',
			'status' => 'read',
			'trash'  => 0,
			'type' => 'received',
			'email'  => null,
			'parent_id'  => null,
		),
		array(
			'id'  => 10,
			'sender_id'  => 2,
			'receiver_id'  => 1,
			'receiver_type' => 'user',
			'message_id'  => 4,
			'template'  => 'user_message',
			'template_data'  => '{"sender":"Another User","sender_id":"2"}',
			'status' => 'read',
			'trash'  => 1,
			'type' => 'received',
			'email'  => null,
			'parent_id'  => null,
		),
		array(
			'id'  => 11,
			'sender_id'  => 2,
			'receiver_id'  => 1,
			'receiver_type' => 'user',
			'message_id'  => 2,
			'template'  => 'project_invite',
			'template_data'  => '{"sender":"Another User","sender_id":"2","project":"Public Test Project","project_id":"2"}',
			'status' => 'unread',
			'trash'  => 0,
			'type' => 'received',
			'email'  => null,
			'parent_id'  => null
		),
		array(
			'id'  => 12,
			'sender_id'  => 1,
			'receiver_id'  => 1,
			'receiver_type' => 'user',
			'message_id'  => 2,
			'template'  => 'project_invite',
			'template_data'  => '{"sender":"Test User","sender_id":"1","group":"Test Project","project_id":"1"}',
			'status' => 'unread',
			'trash'  => 0,
			'type' => 'received',
			'email'  => null,
			'parent_id'  => null
		),
		array(
			'id'  => 13,
			'sender_id'  => 3,
			'receiver_id'  => 1,
			'receiver_type' => 'user',
			'message_id'  => 2,
			'template'  => 'project_request',
			'template_data'  => '{"sender":"Third User","sender_id":"3","group":"Test Project","project_id":"1"}',
			'status' => 'unread',
			'trash'  => 0,
			'type' => 'received',
			'email'  => null,
			'parent_id'  => null
		),
		array(
			'id'  => 14,
			'sender_id'  => 1,
			'receiver_id'  => 1,
			'receiver_type' => 'user',
			'message_id'  => 2,
			'template'  => 'project_request',
			'template_data'  => '{"sender":"Test User","sender_id":"1","group":"Test Project","project_id":"1"}',
			'status' => 'unread',
			'trash'  => 0,
			'type' => 'received',
			'email'  => null,
			'parent_id'  => null
		),
	);
}
?>