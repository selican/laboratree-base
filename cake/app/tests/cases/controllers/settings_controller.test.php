<?php
App::import('Controller','Settings');
App::import('Component', 'RequestHandler');

Mock::generatePartial('RequestHandlerComponent', 'SettingsControllerMockRequestHandlerComponent', array('prefers'));

class SettingsControllerTestSettingsController extends SettingsController {
	var $name = 'Settings';
	var $autoRender = false;

	var $redirectUrl = null;
	var $renderedAction = null;
	var $error = null;
	var $stopped = null;
	
	function redirect($url, $status = null, $exit = true)
	{
		$this->redirectUrl = $url;
	}
	function render($action = null, $layout = null, $file = null)
	{
		$this->renderedAction = $action;
	}

	function cakeError($method, $messages = array())
	{
		if(!isset($this->error))
		{
			$this->error = $method;
		}
	}
	function _stop($status = 0)
	{
		$this->stopped = $status;
	}
}

class SettingsControllerTest extends CakeTestCase {
	var $Settings = null;
	var $fixtures = array('app.helps', 'app.app_category', 'app.app_data', 'app.application', 'app.app_module', 'app.attachment', 'app.digest', 'app.discussion', 'app.doc', 'app.docs_permission', 'app.docs_tag', 'app.docs_type_data', 'app.docs_type_field', 'app.docs_type', 'app.docs_type_row', 'app.docs_version', 'app.group', 'app.groups_address', 'app.groups_association', 'app.groups_award', 'app.groups_interest', 'app.groups_phone', 'app.groups_projects', 'app.groups_publication', 'app.groups_setting', 'app.groups_url', 'app.groups_users', 'app.inbox', 'app.inbox_hash', 'app.interest', 'app.message_archive', 'app.message', 'app.note', 'app.ontology_concept', 'app.preference', 'app.project', 'app.projects_association', 'app.projects_interest', 'app.projects_setting', 'app.projects_url', 'app.projects_users', 'app.role', 'app.setting', 'app.site_role', 'app.tag', 'app.type', 'app.url', 'app.user', 'app.users_address', 'app.users_association', 'app.users_award', 'app.users_education', 'app.users_interest', 'app.users_job', 'app.users_phone', 'app.users_preference', 'app.users_publication', 'app.users_url', 'app.ldap_user');
	
	function startTest() {
		$this->Settings = new SettingsControllerTestSettingsController();
		$this->Settings->constructClasses();
		$this->Settings->Component->initialize($this->Settings);
		
		$this->Settings->Session->write('Auth.User', array(
			'id' => 1,
			'username' => 'testuser',
			'changepass' => 0,
		));
	}
	
	function testSettingsControllerInstance() {
		$this->assertTrue(is_a($this->Settings, 'SettingsController'));
	}

	function testbeforeFilter() {
		$this->Settings->beforeFilter();
	}

	function testGroupEmptyGroupId() {
		$this->Settings->group(null);
		$this->assertEqual($this->Settings->error, 'invalid_field');
	}

	function testGroupStringGroupId() {
                $this->Settings->group('string');
                $this->assertEqual($this->Settings->error, 'invalid_field');
	}

	function testGroupNullGroupId() {
                $this->Settings->group(null);
                $this->assertEqual($this->Settings->error, 'invalid_field');
	}

	function testGroupZeroGroupId() {
                $this->Settings->group(-1);
		$this->assertEqual($this->Settings->error, 'invalid_field');
	}

	function testGroupFindEmptyGroup() {
		$this->Settings->group(12);
		$this->assertEqual($this->Settings->error, 'invalid_field');	
	}
	
	function testGroupInvalidUser() {
		$this->Settings->Session->write('Auth.User', array(null));
		

		$this->Settings->group(1);
		$this->assertEqual($this->Settings->error, 'access_denied');
	}

	function testGroupSetStuff() {
		$this->Settings->group(1);
	}

	function testGroupValidPrefersJsonFormEntityNotSet() {
		$this->Settings->RequestHandler = new SettingsControllerMockRequestHandlerComponent();
		$this->Settings->RequestHandler->setReturnValue('prefers', true);

		$this->Settings->group(1);
		$this->assertEqual($this->Settings->error, 'missing_field');
	}

        function testGroupValidPrefersJsonFormEntitySetEmptyGroot() {
                $this->Settings->RequestHandler = new SettingsControllerMockRequestHandlerComponent();
                $this->Settings->RequestHandler->setReturnValue('prefers', true);

		$this->Settings->params['form']['entity'] = 'something';
                $this->Settings->group(1);

		//$this->assertEqual($this->Settings->error, 'internal_error');
        }

	function testGroupEmptyGroupThreads() {
                $this->Settings->RequestHandler = new SettingsControllerMockRequestHandlerComponent();
                $this->Settings->RequestHandler->setReturnValue('prefers', true);
		
                $this->Settings->params['form']['entity'] = 'something';
                $this->Settings->group(2);

               // $this->assertEqual($this->Settings->error, 'internal_error');
	}

	function testGroupGoodValue() {
                $this->Settings->RequestHandler = new SettingsControllerMockRequestHandlerComponent();
                $this->Settings->RequestHandler->setReturnValue('prefers', true);

                $this->Settings->data['GroupsSetting']['group'] = array(array(
									'group_id' => 1,
									'setting_id' => 2,
									'value' => 2),
									array(
									'group_id' => 2,
                                                                        'setting_id' => 3,
                                                                        'value' => 2));
                $this->Settings->params['form']['entity'] = 'something';
                $this->Settings->group(1);
        }	
	
	function testGroupIsResponseSet() {

	}

	function testGroupSuccessfulGroupThreads() {
		$this->Settings->RequestHandler = new SettingsControllerMockRequestHandlerComponent();
                $this->Settings->RequestHandler->setReturnValue('prefers', true);

		$this->Settings->params['form']['entity'] = array(
								'title' => 'title',
								'description' => 'description');
		$this->Settings->group(7);
	}	

	function testProjectEmptyProjectId() {
		$this->Settings->project(null);
		$this->assertEqual($this->Settings->error, 'invalid_field');
	}

	function testProjectStringProjectId() {
                $this->Settings->project('string');
                $this->assertEqual($this->Settings->error, 'invalid_field');
	}

	function testProjectBoolProjectId() {
                $this->Settings->project(true);
                $this->assertEqual($this->Settings->error, 'invalid_field');
	}

	function testProjectProjectIdLessThanOne() {
                $this->Settings->project(-1);
                $this->assertEqual($this->Settings->error, 'invalid_field');
	}

	function testProjectEmptyProject() {
		$this->Settings->project(12);
		$this->assertEqual($this->Settings->error, 'invalid_field');
	}

	function testProjectAccessDenied() {
		$this->Settings->Session->write('Auth.User', array(null));
		
		$this->Settings->project(1);
		$this->assertEqual($this->Settings->error, 'access_denied');
	}

	function testProjectEmptyData() {
                $this->Settings->project(1);
	}

	function testProjectPrefersJsonNoParams() {
		$this->Settings->RequestHandler = new SettingsControllerMockRequestHandlerComponent();
		$this->Settings->RequestHandler->setReturnValue('prefers', true);

		$this->Settings->project(1);
		$this->assertEqual($this->Settings->error, 'missing_field');
	}

	function testProjectValidNoJson() {
		$this->Settings->data['ProjectsSetting']['project'] = array(array('test' => 'data'));
                $this->Settings->project(1);
	}
	
	function testProjectPrefersJsonEmptyGroot() {
	}

	function testProjectSuccessfulProjectThreads() {
                $this->Settings->RequestHandler = new SettingsControllerMockRequestHandlerComponent();
                $this->Settings->RequestHandler->setReturnValue('prefers', true);

		$this->Settings->params['form']['entity'] = array(
								'title' => 'title',
								'description' => 'description');
                $this->Settings->project(1);
	
	}

	function testCreateSetts() {
//		$this->Settings->_create_setts();
	}

	function testUnfinishedFunctions() {
		$this->Settings->admin_index();
		$this->Settings->admin_add();
		$this->Settings->admin_edit();
		$this->Settings->admin_delete();
		$this->Settings->admin_reorder();
		$this->Settings->admin_reparent();
	}
	function endTest() {
		unset($this->Settings);
		ClassRegistry::flush();	
	}
}
?>
