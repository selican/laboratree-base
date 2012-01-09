<?php
class DigestFixture extends CakeTestFixture {
	var $name = 'Digest';
	var $table = 'digests';
	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'inbox_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'string', 'null' => true, 'default' => NULL));

	var $records = array(
		array(
			'id' => 1,
			'user_id' => 1,
			'inbox_id' => 1,
			'created' => 'Never',
			'modified' => 'Never',));
}
?>
