<?php 
/* SVN FILE: $Id$ */
/* Role Test cases generated on: 2010-12-20 14:59:52 : 1292857192*/
App::import('Model', 'Role');

class RoleTestCase extends CakeTestCase {
	var $Role = null;
	var $fixtures = array('app.helps', 'app.role');

	function startTest() {
		$this->Role =& ClassRegistry::init('Role');
	}

	function testRoleInstance() {
		$this->assertTrue(is_a($this->Role, 'Role'));
	}

	function testRoleFind() {
		$this->Role->recursive = -1;
		$results = $this->Role->find('first');
		$this->assertTrue(!empty($results));

		$expected = array('Role' => array(
			'id'  => 1,
			'table_type' => 'group',
			'table_id' => 1,
			'name' => 'Administrator',
			'read_only' => 1,
		));
		$this->assertEqual($results, $expected);
	}
}
?>
