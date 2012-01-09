<?php 

class WordFixture extends CakeTestFixture {
	var $name = 'Word';
	var $table = 'words';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'word' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'word' => array('column' => 'word', 'unique' => 1))
	);
	var $records = array(
		array(
			'id'  => 1,
			'word' => 'word1',
		),
		array(
			'id'  => 2,
			'word' => 'word2',
		),
		array(
			'id'  => 3,
			'word' => 'word3',
		),
		array(
			'id'  => 4,
			'word' => 'word4',
		),
		array(
			'id'  => 5,
			'word' => 'word5',
		),
		array(
			'id'  => 6,
			'word' => 'word6',
		),
	);
}
?>
