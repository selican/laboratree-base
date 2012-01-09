<?php 
/* SVN FILE: $Id$ */
/* OntologyConcept Fixture generated on: 2010-12-20 14:58:50 : 1292857130*/

class OntologyConceptFixture extends CakeTestFixture {
	var $name = 'OntologyConcept';
	var $table = 'ontology_concepts';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'table_type' => array('type'=>'string', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'table_id' => array('type'=>'integer', 'null' => false, 'default' => NULL),
		'concept_id' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'name' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'score' => array('type'=>'integer', 'null' => false, 'default' => NULL),
		'field' => array('type'=>'string', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'table_type' => array('column' => array('table_type', 'table_id', 'concept_id', 'field'), 'unique' => 1))
	);
	var $records = array(array(
		'id'  => 1,
		'table_type'  => 'User',
		'table_id'  => 1,
		'concept_id'  => '11111/Test',
		'name'  => 'Test',
		'score'  => 100,
		'field'  => 'description'
	));
}
?>
