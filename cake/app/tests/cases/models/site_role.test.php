<?php 
/* SVN FILE: $Id$ */
/* SiteRole Test cases generated on: 2010-12-20 15:00:18 : 1292857218*/
App::import('Model', 'SiteRole');

class SiteRoleTestCase extends CakeTestCase {
	var $SiteRole = null;
	var $fixtures = array('app.helps', 'app.site_role');

	function startTest() {
		$this->SiteRole =& ClassRegistry::init('SiteRole');
	}

	function testSiteRoleInstance() {
		$this->assertTrue(is_a($this->SiteRole, 'SiteRole'));
	}

	function testSiteRoleFind() {
		$this->SiteRole->recursive = -1;
		$results = $this->SiteRole->find('first');
		$this->assertTrue(!empty($results));

		$expected = array('SiteRole' => array(
			'id'  => 1,
			'name'  => 'group.manager',
			'type' => 'group',
			'rank'  => 0
		));
		$this->assertEqual($results, $expected);
	}
}
?>
