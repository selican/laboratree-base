<?php
App::import('Controller','Preferences');
App::import('Component', 'RequestHandler');

Mock::generatePartial('RequestHandlerComponent', 'PreferencesControllerMockRequestHandlerComponent', array('prefers'));

class PreferencesControllerTestPreferencesController extends PreferencesController {
	var $name = 'Preferences';
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

class PreferencesControllerTest extends CakeTestCase {
	var $Preferences = null;
	var $fixtures = array('app.helps', 'app.app_category', 'app.app_data', 'app.application', 'app.app_module', 'app.attachment', 'app.digest', 'app.discussion', 'app.doc', 'app.docs_permission', 'app.docs_tag', 'app.docs_type_data', 'app.docs_type_field', 'app.docs_type', 'app.docs_type_row', 'app.docs_version', 'app.group', 'app.groups_address', 'app.groups_association', 'app.groups_award', 'app.groups_interest', 'app.groups_phone', 'app.groups_projects', 'app.groups_publication', 'app.groups_setting', 'app.groups_url', 'app.groups_users', 'app.inbox', 'app.inbox_hash', 'app.interest', 'app.message_archive', 'app.message', 'app.note', 'app.ontology_concept', 'app.preference', 'app.project', 'app.projects_association', 'app.projects_interest', 'app.projects_setting', 'app.projects_url', 'app.projects_users', 'app.role', 'app.setting', 'app.site_role', 'app.tag', 'app.type', 'app.url', 'app.user', 'app.users_address', 'app.users_association', 'app.users_award', 'app.users_education', 'app.users_interest', 'app.users_job', 'app.users_phone', 'app.users_preference', 'app.users_publication', 'app.users_url', 'app.ldap_user');
	
	function startTest() {
		$this->Preferences = new PreferencesControllerTestPreferencesController();
		$this->Preferences->constructClasses();
		$this->Preferences->Component->initialize($this->Preferences);
		
		$this->Preferences->Session->write('Auth.User', array(
			'id' => 1,
			'username' => 'testuser',
			'changepass' => 0,
		));
	}
	
	function testPreferencesControllerInstance() {
		$this->assertTrue(is_a($this->Preferences, 'PreferencesController'));
	}

	function testbeforeFilter() {
		$this->Preferences->beforeFilter();
	}
/*******************************************************************************************************/
	function testIndexNoData() {
		$this->Preferences->index();
	}

	function testIndexPopulatedData() {
		$this->Preferences->data['UsersPreference'] =array(array(array(
										
									'table_type' => 'test',
									'table_id' => 1,
									'user_id' => 2,
									'preference_id' => 1,
									'value' => 'value')));
	
		$this->Preferences->index();
	}



	function testIndexPrefersJsonDefaultAction() {
		$this->Preferences->RequestHandler = new PreferencesControllerMockRequestHandlerComponent();
		$this->Preferences->RequestHandler->setReturnValue('prefers', true);


		$this->Preferences->index();
	}

	function testIndexPrefersJsonPreferencesActionParamsNotSet() {
		$this->Preferences->RequestHandler = new PreferencesControllerMockRequestHandlerComponent();
                $this->Preferences->RequestHandler->setReturnValue('prefers', true);

		$this->Preferences->params['form']['action'] = 'preferences';

		$this->Preferences->index();
		$this->assertEqual($this->Preferences->error, 'missing_field');
	}

	function testIndexPrefersJsonPreferencesGroupValid() {
		$this->Preferences->RequestHandler = new PreferencesControllerMockRequestHandlerComponent();
                $this->Preferences->RequestHandler->setReturnValue('prefers', true);

                $this->Preferences->params['form']['action'] = 'preferences';
		$this->Preferences->params['form']['entity'] = 'group-1';

                $this->Preferences->index();
	}
 
	function testIndexPrefersJsonPreferencesProjectsValid() {
                $this->Preferences->RequestHandler = new PreferencesControllerMockRequestHandlerComponent();
                $this->Preferences->RequestHandler->setReturnValue('prefers', true);

                $this->Preferences->params['form']['action'] = 'preferences';
                $this->Preferences->params['form']['entity'] = 'project-31';

                $this->Preferences->index();
        }
/*****************************************************************************************************/
	function testToDoAdminIndex() {
		$this->Preferences->admin_index();
		$this->assertEqual($this->Preferences->error, null);
	}

	function testToDoAdminAdd() {
		$this->Preferences->admin_add();
                $this->assertEqual($this->Preferences->error, null);
	}

	function testToDoAdminEdit() {
		$this->Preferences->admin_edit();
                $this->assertEqual($this->Preferences->error, null);
	}

	function testToDoAdminDelete() {
		$this->Preferences->admin_delete();
                $this->assertEqual($this->Preferences->error, null);
	}

	function testToDoAdminReorder() {
		$this->Preferences->admin_reorder();
                $this->assertEqual($this->Preferences->error, null);
	}

	function testToDoAdminReparent() {
		$this->Preferences->admin_reparent();
                $this->assertEqual($this->Preferences->error, null);
	}
/*****************************************************************************************************/
	function testCreatePrefs() {
//		$this->Preferences->_create_prefs();
	}





	function endTest() {
		unset($this->Preferences);
		ClassRegistry::flush();	
	}
}
?>
