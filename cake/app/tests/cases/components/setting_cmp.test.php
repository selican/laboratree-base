<?php
App::import('Controller', 'App');
App::import('Component', 'SettingCmp');

class SettingCmpComponentTestController extends AppController {
	var $name = 'Test';
	var $uses = array();
	var $components = array(
		'SettingCmp',
	);
}

class SettingCmpTest extends CakeTestCase
{
	var $fixtures = array('app.helps', 'app.app_category', 'app.app_data', 'app.application', 'app.app_module', 'app.attachment', 'app.digest','app.discussion', 'app.doc', 'app.docs_permission', 'app.docs_tag', 'app.docs_type_data', 'app.docs_type_field', 'app.docs_type', 'app.docs_type_row', 'app.docs_version', 'app.group', 'app.groups_address', 'app.groups_association', 'app.groups_award', 'app.groups_interest', 'app.groups_phone', 'app.groups_projects', 'app.groups_publication', 'app.groups_setting', 'app.groups_url', 'app.groups_users', 'app.inbox', 'app.inbox_hash', 'app.interest', 'app.message_archive', 'app.message', 'app.note', 'app.ontology_concept', 'app.preference', 'app.project', 'app.projects_association', 'app.projects_interest', 'app.projects_setting', 'app.projects_url', 'app.projects_users', 'app.role', 'app.setting', 'app.site_role', 'app.tag', 'app.type', 'app.url', 'app.user', 'app.users_address', 'app.users_association', 'app.users_award', 'app.users_education', 'app.users_interest', 'app.users_job', 'app.users_phone', 'app.users_preference', 'app.users_publication', 'app.users_url', 'app.ldap_user');

	function startTest()
	{
		$this->Controller = new SettingCmpComponentTestController();
		$this->Controller->constructClasses();
		$this->Controller->Component->initialize($this->Controller);
	}

	function testSettingInstance() {
		$this->assertTrue(is_a($this->Controller->SettingCmp, 'SettingCmpComponent'));
	}

	function testStartup() {
		$this->Controller->SettingCmp->startup(&$controller);
	}

	function testGetValueEmptyTableType() {
		try {
			$this->Controller->SettingCmp->get_value(null, 1, 'setting');
			$this->fail();
		}

		catch(invalidArgumentException $e) {
			$this->pass();
		}
	}

	function testGetValueTableTypeNotInArray() {
                try {
                        $this->Controller->SettingCmp->get_value('string', 1, 'setting');
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testGetValueEmptyTableID() {
                try {
                        $this->Controller->SettingCmp->get_value('group', null, 'setting');
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testGetValueNonNumericTableID() {
                try {
                        $this->Controller->SettingCmp->get_value('group', 'string', 'setting');
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testGetValueZeroTableID() {
                try {
                        $this->Controller->SettingCmp->get_value('group', '0', 'setting');
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testGetValueEmptySetting() {
                try {
                        $this->Controller->SettingCmp->get_value('group', '1', null);
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testGetValueSettingNotString() {
	//Test Integer
                try {
                        $this->Controller->SettingCmp->get_value('group', '1', 1);
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }

	//test Array
                try {
                        $this->Controller->SettingCmp->get_value('group', 1, array('test' => 'data'));
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testGetEmptySSet() {
		try {
                        $this->Controller->SettingCmp->get_value('group', 1, 'TESTTHIS');
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}
	
	function testGetValueGroupValid() {
		$this->Controller->SettingCmp->get_value('group', 1, 'group.chat.password');
	}

        function testGetValueProjectValid() {
                $this->Controller->SettingCmp->get_value('project', 1, 'group.chat.password');
        }
/****************************************************************************************************/
	function testGetIdNullSetting() {
		try {
			$this->Controller->SettingCmp->get_id(null);
			$this->fail();
		}

		catch(invalidArgumentException $e) {
			$this->pass();
		}
	}

	function testGetIdBoolSetting() {
                try {
                        $this->Controller->SettingCmp->get_id(true);
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testGetIdExplodeTest() {
		try {
			$this->Controller->SettingCmp->get_id('TEST');
			$this->fail();
		}

		catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testGetIdGroupValid() {
                $this->Controller->SettingCmp->get_id('group.chat.password');
        }

        function testGetIdProjectValid() {
                $this->Controller->SettingCmp->get_id('group.chat.password');
	}
/****************************************************************************************************/
        function testCheckNullTableType() {
                try {
                        $this->Controller->SettingCmp->check(null, 1, 'string', 'string');
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
        }

        function testCheckTableTypeNotInArray() {
                try {
                        $this->Controller->SettingCmp->check('string', 1, 'string', 'string');
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
        }

	function testCheckNullTableID() {
                try {
                        $this->Controller->SettingCmp->check('group', null, 'string', 'string');
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testCheckBoolTableID() {
                try {
                        $this->Controller->SettingCmp->check('group', true, 'string', 'string');
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testCheckStringTableID() {
                try {
                        $this->Controller->SettingCmp->check('group', 'string', 'string', 'string');
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}
	
	function testCheckNegativeTableID() {
                try {
                        $this->Controller->SettingCmp->check('group', -1, 'string', 'string');
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testCheckEmptySetting() {
                try {
                        $this->Controller->SettingCmp->check('group', 1, null, 'string');
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }	
	}

	function testCheckBoolSetting() {
                try {
                        $this->Controller->SettingCmp->check('group', 1, true, 'string');
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testCheckIntSetting() {
                try {
                        $this->Controller->SettingCmp->check('group', 1, 1, 'string');
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testCheckEmptyExpected() {
                try {
                        $this->Controller->SettingCmp->check('group', 1, 'string', null);
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testCheckBoolExpected() {
                try {
                        $this->Controller->SettingCmp->check('group', 1, 'string', false);
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testCheckIntExpected() {
                try {
                        $this->Controller->SettingCmp->check('group', 1, 'string', 1);
                        $this->fail();
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}
	
	function testCheckGroupExpectedReturnFalse() {
			$this->Controller->SettingCmp->check('group', 1, 'group.chat.password', 'changeme');
        }
        
	function testCheckGroupExpectedReturnTrue() {
                        $this->Controller->SettingCmp->check('group', 1, 'group.chat.password', 'changeme');
        }
/*******************************************************************************************************************/
	function testHandleSettingEmptyTableType() {
		try {
			$this->Controller->SettingCmp->handleSetting(null, 1, 1, 1);
		}

		catch(invalidArgumentException $e) {
			$this->pass();	
		}
	}

	function testHandleSettingTableTypeNotInArray() {
                try {
                        $this->Controller->SettingCmp->handleSetting('string', 1, 1, 1);
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testHandleSettingEmptyTableId() {
                try {
                        $this->Controller->SettingCmp->handleSetting('group', null, 1, 1);
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testHandleSettingBoolTableId() {
                try {
                        $this->Controller->SettingCmp->handleSetting('group', true, 1, 1);
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testHandleSettingStringTableId() {
                try {
                        $this->Controller->SettingCmp->handleSetting('group', 'string', 1, 1);
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testHandleSettingNegativeTableId() {
                try {
                        $this->Controller->SettingCmp->handleSetting('group', -1, 1, 1);
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testHandleSettingEmptySettingId() {
                try {
                        $this->Controller->SettingCmp->handleSetting('group', 1, null, 1);
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
        }

	function testHandleSettingBoolSettingId() {
                try {
                        $this->Controller->SettingCmp->handleSetting('group', 1, true, 1);
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
      	}

	function testHandleSettingStringSettingId() {
                try {
                        $this->Controller->SettingCmp->handleSetting('group', 1, 'string', 1);
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testHandleSettingsNegativeSettingId() {
                try {
                        $this->Controller->SettingCmp->handleSetting('group', 1, -1, 1);
                }

                catch(invalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testHandleSettingsValid() {
		$this->Controller->SettingCmp->handleSetting('group', 1, 7, 'group.chat.password');
	}

	function endTest() {
		unset($this->Controller);
		ClassRegistry::flush();	
	}
}
?>
