<?php
App::import('Controller','Pages');
App::import('Component', 'RequestHandler');

Mock::generatePartial('RequestHandlerComponent', 'PagesControllerMockRequestHandlerComponent', array('prefers'));

class PagesControllerTestPagesController extends PagesController {
	var $name = 'Pages';
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

class PagesControllerTest extends CakeTestCase {
	var $Pages = null;
	var $fixtures = array('app.helps', 'app.app_category', 'app.app_data', 'app.application', 'app.app_module', 'app.attachment', 'app.digest', 'app.discussion', 'app.doc', 'app.docs_permission', 'app.docs_tag', 'app.docs_type_data', 'app.docs_type_field', 'app.docs_type', 'app.docs_type_row', 'app.docs_version', 'app.group', 'app.groups_address', 'app.groups_association', 'app.groups_award', 'app.groups_interest', 'app.groups_phone', 'app.groups_projects', 'app.groups_publication', 'app.groups_setting', 'app.groups_url', 'app.groups_users', 'app.inbox', 'app.inbox_hash', 'app.interest', 'app.message_archive', 'app.message', 'app.note', 'app.ontology_concept', 'app.preference', 'app.project', 'app.projects_association', 'app.projects_interest', 'app.projects_setting', 'app.projects_url', 'app.projects_users', 'app.role', 'app.setting', 'app.site_role', 'app.tag', 'app.type', 'app.url', 'app.user', 'app.users_address', 'app.users_association', 'app.users_award', 'app.users_education', 'app.users_interest', 'app.users_job', 'app.users_phone', 'app.users_preference', 'app.users_publication', 'app.users_url', 'app.ldap_user');
	
	function startTest() {
		$this->Pages = new PagesControllerTestPagesController();
		$this->Pages->constructClasses();
		$this->Pages->Component->initialize($this->Pages);
		
		$this->Pages->Session->write('Auth.User', array(
			'id' => 1,
			'username' => 'testuser',
			'changepass' => 0,
		));
	}
	
	function testPagesControllerInstance() {
		$this->assertTrue(is_a($this->Pages, 'PagesController'));
	}

	function testDisplay()
	{
		$path = 'home';

		$this->Pages->params = Router::parse('pages/display/' . $path);
		$this->Pages->beforeFilter();
		$this->Pages->Component->startup($this->Pages);

		$this->Pages->display($path);

		$this->assertEqual($this->Pages->renderedAction, 'user/' . $path);
	}

	function testDisplayNullPath()
	{
		$path = null;

		$this->Pages->params = Router::parse('pages/display/' . $path);
		$this->Pages->beforeFilter();
		$this->Pages->Component->startup($this->Pages);

		$this->Pages->display($path);

		$this->assertEqual($this->Pages->redirectUrl, '/');
	}

	function endTest() {
		unset($this->Pages);
		ClassRegistry::flush();	
	}
}
?>
