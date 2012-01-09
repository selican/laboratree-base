<?php
App::import('Controller', 'App');
App::import('Component', 'Pref');

class PrefComponentTestController extends AppController {
	var $name = 'Test';
	var $uses = array();
	var $components = array(
		'Pref',
	);
}

class PrefTest extends CakeTestCase
{
	var $fixtures = array('app.helps', 'app.app_category', 'app.app_data', 'app.application', 'app.app_module', 'app.attachment', 'app.digest','app.discussion', 'app.doc', 'app.docs_permission', 'app.docs_tag', 'app.docs_type_data', 'app.docs_type_field', 'app.docs_type', 'app.docs_type_row', 'app.docs_version', 'app.group', 'app.groups_address', 'app.groups_association', 'app.groups_award', 'app.groups_interest', 'app.groups_phone', 'app.groups_projects', 'app.groups_publication', 'app.groups_setting', 'app.groups_url', 'app.groups_users', 'app.inbox', 'app.inbox_hash', 'app.interest', 'app.message_archive', 'app.message', 'app.note', 'app.ontology_concept', 'app.preference', 'app.project', 'app.projects_association', 'app.projects_interest', 'app.projects_setting', 'app.projects_url', 'app.projects_users', 'app.role', 'app.setting', 'app.site_role', 'app.tag', 'app.type', 'app.url', 'app.user', 'app.users_address', 'app.users_association', 'app.users_award', 'app.users_education', 'app.users_interest', 'app.users_job', 'app.users_phone', 'app.users_preference', 'app.users_publication', 'app.users_url', 'app.ldap_user');

	function startTest()
	{
		$this->Controller = new PrefComponentTestController();
		$this->Controller->constructClasses();
		$this->Controller->Component->initialize($this->Controller);
	}

	function testSettingInstance() {
		$this->assertTrue(is_a($this->Controller->Pref, 'PrefComponent'));
	}

	function testStartup() {
		$this->Controller->Pref->startup(&$controller);
	}

	function testGetEmptyTableType() {
		try {
			$this->Controller->Pref->get(null, 1, 'setting', 1);
			$this->fail();
		}

		catch(invalidArgumentException $e) {
			$this->pass();
		}
	}

	function testGetTableTypeNotInArray() {
                try {
                        $this->Controller->Pref->get('string', 1, 'setting', 1);
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testGetEmptyTableID() {
                try {
                        $this->Controller->Pref->get('group', null, 'setting', 1);
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testGetNonNumericTableID() {
                try {
                        $this->Controller->Pref->get('group', 'string', 'setting', 1);
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testGetZeroTableID() {
                try {
                        $this->Controller->Pref->get('group', '0', 'setting', 1);
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testGetEmptySetting() {
                try {
                        $this->Controller->Pref->get('group', '1', null, 1);
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testGetSettingNotString() {
	//Test Integer
                try {
                        $this->Controller->Pref->get('group', '1', 1, 1);
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }

	//test Array
                try {
                        $this->Controller->Pref->get('group', 1, array('test' => 'data'), 1);
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testGetEmptySSet() {
		try {
                        $this->Controller->Pref->get('group', 1, 'TESTTHIS', 1);
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}
	
	function testGetStringNullUserId() {
                try {
                        $this->Controller->Pref->get('group', 1, 'string', null);
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
        }

	function testGetStringUserId() {
		try {
                        $this->Controller->Pref->get('group', 1, 'string', 1);
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testGetBoolUserId() {
                try {
                        $this->Controller->Pref->get('group', 1, 'string', true);
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
        }


	function testGetNegativeUserId() {
                try {
                        $this->Controller->Pref->get('group', 1, 'string', -1);
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
        }

	function testGetGroupValid() {
		$this->Controller->Pref->get('group', 1, 'group.documents.add.email', 1);
	}

        function testGetProjectValid() {
                $this->Controller->Pref->get('project', 1, 'group.documents.add.email', 1);
        }
/****************************************************************************************************/
        function testCheckNullTableType() {
                try {
                        $this->Controller->Pref->check(null, 1, 'string', 'string', 1);
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
        }

        function testCheckTableTypeNotInArray() {
                try {
                        $this->Controller->Pref->check('string', 1, 'string', 'string', 1);
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
        }

	function testCheckNullTableID() {
                try {
                        $this->Controller->Pref->check('group', null, 'string', 'string', 1);
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testCheckBoolTableID() {
                try {
                        $this->Controller->Pref->check('group', true, 'string', 'string', 1);
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testCheckStringTableID() {
                try {
                        $this->Controller->Pref->check('group', 'string', 'string', 'string', 1);
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}
	
	function testCheckNegativeTableID() {
                try {
                        $this->Controller->Pref->check('group', -1, 'string', 'string', 1);
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testCheckEmptyPreference() {
                try {
                        $this->Controller->Pref->check('group', 1, null, 'string', 1);
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }	
	}

	function testCheckBoolPreference() {
                try {
                        $this->Controller->Pref->check('group', 1, true, 'string', 1);
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testCheckIntPreference() {
                try {
                        $this->Controller->Pref->check('group', 1, 1, 'string', 1);
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testCheckEmptyExpected() {
                try {
                        $this->Controller->Pref->check('group', 1, 'string', null, 1);
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testCheckBoolExpected() {
                try {
                        $this->Controller->Pref->check('group', 1, 'string', false, 1);
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}
	function testNullUserId() {
                try {
                        $this->Controller->Pref->check('group', 1, 'string', 'string', null);
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
        }
	
	function testCheckBoolUserId() {
 		try {
	                $this->Controller->Pref->check('group', 1, 'string', 'string', true);
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
        }

	function testCheckStringUserId() {
	       try {
                        $this->Controller->Pref->check('group', 1, 'string', 'string', 'string');
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
        }

	function testCheckNegativeUserId() {
                try {
                        $this->Controller->Pref->check('group', 1, 'string', 'string', -1);
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
        }
	
	function testCheckIntExpected() {
                try {
                        $this->Controller->Pref->check('group', 1, 'string', 1, 1);
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}
	
	function testCheckGroupExpectedReturnFalse() {
			$this->Controller->Pref->check('group', 1, 'group.documents.add.email', 'changeme', 1);
        }
        
	function testCheckGroupExpectedReturnTrue() {
                        $this->Controller->Pref->check('group', 1, 'group.documents.add.email', 'changeme', 1);
        }

	function endTest() {
		unset($this->Controller);
		ClassRegistry::flush();	
	}
}
?>
