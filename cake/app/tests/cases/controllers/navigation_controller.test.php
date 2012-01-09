<?php
App::import('Controller','Navigation');
App::import('Component', 'RequestHandler');

Mock::generatePartial('RequestHandlerComponent', 'NavigationControllerMockRequestHandlerComponent', array('prefers'));

class NavigationControllerTestNavigationController extends NavigationController {
	var $name = 'Navigation';
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

class NavigationControllerTest extends CakeTestCase {
	var $Navigation = null;
	var $fixtures = array('app.helps', 'app.app_category', 'app.app_data', 'app.application', 'app.app_module', 'app.attachment', 'app.digest', 'app.discussion', 'app.doc', 'app.docs_permission', 'app.docs_tag', 'app.docs_type_data', 'app.docs_type_field', 'app.docs_type', 'app.docs_type_row', 'app.docs_version', 'app.group', 'app.groups_address', 'app.groups_association', 'app.groups_award', 'app.groups_interest', 'app.groups_phone', 'app.groups_projects', 'app.groups_publication', 'app.groups_setting', 'app.groups_url', 'app.groups_users', 'app.inbox', 'app.inbox_hash', 'app.interest', 'app.message_archive', 'app.message', 'app.note', 'app.ontology_concept', 'app.preference', 'app.project', 'app.projects_association', 'app.projects_interest', 'app.projects_setting', 'app.projects_url', 'app.projects_users', 'app.role', 'app.setting', 'app.site_role', 'app.tag', 'app.type', 'app.url', 'app.user', 'app.users_address', 'app.users_association', 'app.users_award', 'app.users_education', 'app.users_interest', 'app.users_job', 'app.users_phone', 'app.users_preference', 'app.users_publication', 'app.users_url', 'app.ldap_user');
	
	function startTest() {
		$this->Navigation = new NavigationControllerTestNavigationController();
		$this->Navigation->constructClasses();
		$this->Navigation->Component->initialize($this->Navigation);
		
		$this->Navigation->Session->write('Auth.User', array(
			'id' => 1,
			'username' => 'testuser',
			'changepass' => 0,
		));
	}
	
	function testNavigationControllerInstance() {
		$this->assertTrue(is_a($this->Navigation, 'NavigationController'));
	}

	function testTreeNullController()
	{
		$controller = null;
		$action = 'valid';
		$role = 'valid';

		$this->Navigation->params = Router::parse('navigation/tree/' . $controller . '/' . $action . '/' . $role);
		$this->Navigation->beforeFilter();
		$this->Navigation->Component->startup($this->Navigation);

		$this->Navigation->tree($controller, $action, $role);
		
		$this->assertEqual($this->Navigation->error, 'missing_field');
	}

	function testTreeNullAction()
	{
		$controller = 'valid';
		$action = null;
		$role = 'valid';

		$this->Navigation->params = Router::parse('navigation/tree/' . $controller . '/' . $action . '/' . $role);
		$this->Navigation->beforeFilter();
		$this->Navigation->Component->startup($this->Navigation);

		$this->Navigation->tree($controller, $action, $role);
		
		$this->assertEqual($this->Navigation->error, 'missing_field');
	}

	function testTreeNullRole()
	{
		$controller = 'valid';
		$action = 'valid';
		$role = null;

		$this->Navigation->params = Router::parse('navigation/tree/' . $controller . '/' . $action . '/' . $role);
		$this->Navigation->beforeFilter();
		$this->Navigation->Component->startup($this->Navigation);

		$this->Navigation->tree($controller, $action, $role);
		
		$this->assertEqual($this->Navigation->error, 'missing_field');
	}

	function testAdminIndexNoJson() {
		$this->Navigation->admin_index();
		if (!'pageName' == '1 - Admin Navigation')
			$this->fail();
	}

	function testAdminIndexJson() {
		$this->Navigation->RequestHandler = new NavigationControllerMockRequestHandlerComponent();
		$this->Navigation->RequestHandler->setReturnValue('prefers', true);
		$this->Navigation->params['form']['node'] = 1;

		$this->Navigation->admin_index();
	}

	function testAdminIndexEmptyParent() {
                $this->Navigation->RequestHandler = new NavigationControllerMockRequestHandlerComponent();
                $this->Navigation->RequestHandler->setReturnValue('prefers', true);
                $this->Navigation->params['form']['node'] = 0;

                $this->Navigation->admin_index();
		$this->assertEqual($this->Navigation->error, 'invalid_field');
        }	

	function testAdminAddNoJson() {
		$this->Navigation->admin_add(null);
		$this->assertEqual($this->Navigation->error, 'error404');
	}

	function testAdminAddEmptyParentId() {
                $this->Navigation->RequestHandler = new NavigationControllerMockRequestHandlerComponent();
                $this->Navigation->RequestHandler->setReturnValue('prefers', true);

		$this->Navigation->admin_add(null);
	}

	function testAdminAddEmptyParent() {
                $this->Navigation->RequestHandler = new NavigationControllerMockRequestHandlerComponent();
                $this->Navigation->RequestHandler->setReturnValue('prefers', true);

		$this->Navigation->admin_add(1);
		$this->assertEqual($this->Navigation->error, 'invalid_field');
	}

	function testAdminAddEmptyData() {
                $this->Navigation->RequestHandler = new NavigationControllerMockRequestHandlerComponent();
                $this->Navigation->RequestHandler->setReturnValue('prefers', true);

                $this->Navigation->admin_add(1);
	}

	function testAdminAddEmptyController() {
                $this->Navigation->RequestHandler = new NavigationControllerMockRequestHandlerComponent();
                $this->Navigation->RequestHandler->setReturnValue('prefers', true);
		$this->Navigation->data['Navigation']['something'] = 'something'; 
                
		$this->Navigation->admin_add(1);
	}

	function testAdminEditNoJson() {
		$this->Navigation->admin_edit(null);
                $this->assertEqual($this->Navigation->error, 'error404');
	}

	function testAdminEditEmptyNavID() {
		$this->Navigation->RequestHandler = new NavigationControllerMockRequestHandlerComponent();
                $this->Navigation->RequestHandler->setReturnValue('prefers', true);

		$this->Navigation->admin_edit(null);
		$this->assertEqual($this->Navigation->error, 'missing_field');
	}

	function testAdminEditEmptyItem() {
                $this->Navigation->RequestHandler = new NavigationControllerMockRequestHandlerComponent();
                $this->Navigation->RequestHandler->setReturnValue('prefers', true);

                $this->Navigation->admin_edit('0');
                $this->assertEqual($this->Navigation->error, 'invalid_field');
	}

	function testAdminEditActionSet() {
                $this->Navigation->RequestHandler = new NavigationControllerMockRequestHandlerComponent();
                $this->Navigation->RequestHandler->setReturnValue('prefers', true);
		$this->Navigation->params['form']['action'] = 'something';

                $this->Navigation->admin_edit(1);
                $this->assertEqual($this->Navigation->error, 'invalid_field');
	}

	function testAdminEditValidData() {
                $this->Navigation->RequestHandler = new NavigationControllerMockRequestHandlerComponent();
                $this->Navigation->RequestHandler->setReturnValue('prefers', true);
                $this->Navigation->params['form']['action'] = 'something';
		$this->Navigation->data['Navigation']['something'] = 'something';

                $this->Navigation->admin_edit(1);
                $this->assertEqual($this->Navigation->error, 'invalid_field');
	}

	function testAdminDeleteNoJson() {
		$this->Navigation->admin_delete(null);
		$this->assertEqual($this->Navigation->error, 'error404');
	}

	function testAdminDeleteNullNodeId() {
                $this->Navigation->RequestHandler = new NavigationControllerMockRequestHandlerComponent();
                $this->Navigation->RequestHandler->setReturnValue('prefers', true);

                $this->Navigation->admin_delete(null);
                $this->assertEqual($this->Navigation->error, 'missing_field');
	}

	function testAdminDeleteAccessDenied() {
                $this->Navigation->RequestHandler = new NavigationControllerMockRequestHandlerComponent();
                $this->Navigation->RequestHandler->setReturnValue('prefers', true);

                $this->Navigation->admin_delete(1);
                $this->assertEqual($this->Navigation->error, 'access_denied');
        }
	
	function testAdminPopulate() {
//		$this->Navigation->admin_populate();
	}

	function testAdminReorderNoJson() {
		$this->Navigation->admin_reorder();
		$this->assertEqual($this->Navigation->error, 'error404');
	}

	function testAdminReorderNoNodeParams() {
                $this->Navigation->RequestHandler = new NavigationControllerMockRequestHandlerComponent();
                $this->Navigation->RequestHandler->setReturnValue('prefers', true);

		$this->Navigation->admin_reorder();
                $this->assertEqual($this->Navigation->error, 'missing_field');
	}

	function testAdminReorderNoDeltaParams() {
                $this->Navigation->RequestHandler = new NavigationControllerMockRequestHandlerComponent();
                $this->Navigation->RequestHandler->setReturnValue('prefers', true);
                $this->Navigation->params['form']['node'] = 'something';

                $this->Navigation->admin_reorder();
                $this->assertEqual($this->Navigation->error, 'missing_field');
	}
	
	function testAdminReorderNonNumericNodeParams() {
	        $this->Navigation->RequestHandler = new NavigationControllerMockRequestHandlerComponent();
                $this->Navigation->RequestHandler->setReturnValue('prefers', true);
                $this->Navigation->params['form']['node'] = 'something';
		$this->Navigation->params['form']['delta'] = 'something';

                $this->Navigation->admin_reorder();
                $this->assertEqual($this->Navigation->error, 'invalid_field');
	}

	function testAdminReorderNonNumericDeltaParams() {
		$this->Navigation->RequestHandler = new NavigationControllerMockRequestHandlerComponent();
                $this->Navigation->RequestHandler->setReturnValue('prefers', true);
                $this->Navigation->params['form']['node'] = 1;
                $this->Navigation->params['form']['delta'] = 'something';

                $this->Navigation->admin_reorder();
                $this->assertEqual($this->Navigation->error, 'invalid_field');
	}

        function testAdminReorderEmptyNode() {
                $this->Navigation->RequestHandler = new NavigationControllerMockRequestHandlerComponent();
                $this->Navigation->RequestHandler->setReturnValue('prefers', true);
                $this->Navigation->params['form']['node'] = 0;
                $this->Navigation->params['form']['delta'] = 1;

                $this->Navigation->admin_reorder();
                $this->assertEqual($this->Navigation->error, 'invalid_field');
        }

	function testAdminReorderAccessDenied() {
                $this->Navigation->RequestHandler = new NavigationControllerMockRequestHandlerComponent();
                $this->Navigation->RequestHandler->setReturnValue('prefers', true);
                $this->Navigation->params['form']['node'] = 1;
                $this->Navigation->params['form']['delta'] = 1;

                $this->Navigation->admin_reorder();
                $this->assertEqual($this->Navigation->error, 'access_denied');
        }

	function testAdminReorderSuccess() {
                $this->Navigation->RequestHandler = new NavigationControllerMockRequestHandlerComponent();
                $this->Navigation->RequestHandler->setReturnValue('prefers', true);
                $this->Navigation->params['form']['node'] = 2;
                $this->Navigation->params['form']['delta'] = 3;

                $this->Navigation->Session->write('Auth.User', array(
                        'id' => 2,
                        'username' => 'anotheruser',
                        'changepass' => 0,
                        'admin' => 1,
                  ));

                $this->Navigation->admin_reorder();
                $this->assertNotEqual($this->Navigation->error, 'internal_error');
	}


	function testAdminReparentNoJson() {
		$this->Navigation->admin_reparent();
		$this->assertEqual($this->Navigation->error, 'error404');
	}

	function testAdminReparentMissingNodeParams() {
                $this->Navigation->RequestHandler = new NavigationControllerMockRequestHandlerComponent();
                $this->Navigation->RequestHandler->setReturnValue('prefers', true);

                $this->Navigation->admin_reparent();
                $this->assertEqual($this->Navigation->error, 'missing_field');
	}

	function testAdminReparentMissingParentParams() {
                $this->Navigation->RequestHandler = new NavigationControllerMockRequestHandlerComponent();
                $this->Navigation->RequestHandler->setReturnValue('prefers', true);
                $this->Navigation->params['form']['node'] = 'something';

                $this->Navigation->admin_reparent();
                $this->assertEqual($this->Navigation->error, 'missing_field');
	}

	function testAdminReparentMissingPositionParams() {
                $this->Navigation->RequestHandler = new NavigationControllerMockRequestHandlerComponent();
                $this->Navigation->RequestHandler->setReturnValue('prefers', true);
                $this->Navigation->params['form']['node'] = 'something';
                $this->Navigation->params['form']['parent'] = 'something';

                $this->Navigation->admin_reparent();
                $this->assertEqual($this->Navigation->error, 'missing_field');
	}

	function testAdminReparentNonNumericNodeParams() {
                $this->Navigation->RequestHandler = new NavigationControllerMockRequestHandlerComponent();
                $this->Navigation->RequestHandler->setReturnValue('prefers', true);
                $this->Navigation->params['form']['node'] = 'something';
                $this->Navigation->params['form']['parent'] = 'something';
                $this->Navigation->params['form']['position'] = 'something';

                $this->Navigation->admin_reparent();
                $this->assertEqual($this->Navigation->error, 'invalid_field');
	}

	function testAdminReparentNonNumericParentParams() {
                $this->Navigation->RequestHandler = new NavigationControllerMockRequestHandlerComponent();
                $this->Navigation->RequestHandler->setReturnValue('prefers', true);
                $this->Navigation->params['form']['node'] = 1;
                $this->Navigation->params['form']['parent'] = 'something';
                $this->Navigation->params['form']['position'] = 'something';

                $this->Navigation->admin_reparent();
                $this->assertEqual($this->Navigation->error, 'invalid_field');
	}

	function testAdminReparentNonNumericPositionParams() {
                $this->Navigation->RequestHandler = new NavigationControllerMockRequestHandlerComponent();
                $this->Navigation->RequestHandler->setReturnValue('prefers', true);
                $this->Navigation->params['form']['node'] = 1;
                $this->Navigation->params['form']['parent'] = 1;
                $this->Navigation->params['form']['position'] = 'something';

                $this->Navigation->admin_reparent();
                $this->assertEqual($this->Navigation->error, 'invalid_field');
	}

        function testAdminReparentEmptyNode() {
                $this->Navigation->RequestHandler = new NavigationControllerMockRequestHandlerComponent();
                $this->Navigation->RequestHandler->setReturnValue('prefers', true);
                $this->Navigation->params['form']['node'] = 0;
                $this->Navigation->params['form']['parent'] = 1;
                $this->Navigation->params['form']['position'] = 1;

                $this->Navigation->admin_reparent();
                $this->assertEqual($this->Navigation->error, 'invalid_field');
        }
        
	function testAdminReparentEmptyParent() {
                $this->Navigation->RequestHandler = new NavigationControllerMockRequestHandlerComponent();
                $this->Navigation->RequestHandler->setReturnValue('prefers', true);
                $this->Navigation->params['form']['node'] = 1;
                $this->Navigation->params['form']['parent'] = 0;
                $this->Navigation->params['form']['position'] = 1;

                $this->Navigation->admin_reparent();
                $this->assertEqual($this->Navigation->error, 'invalid_field');
        }

	function testAdminReparentAccessDenied() {
                $this->Navigation->RequestHandler = new NavigationControllerMockRequestHandlerComponent();
                $this->Navigation->RequestHandler->setReturnValue('prefers', true);
                $this->Navigation->params['form']['node'] = 1;
                $this->Navigation->params['form']['parent'] = 1;
                $this->Navigation->params['form']['position'] = 1 ;

                $this->Navigation->admin_reparent();
                $this->assertEqual($this->Navigation->error, 'access_denied');
        }

	function testAdminReparentCantSave() {
                $this->Navigation->RequestHandler = new NavigationControllerMockRequestHandlerComponent();
                $this->Navigation->RequestHandler->setReturnValue('prefers', true);
                $this->Navigation->params['form']['node'] = 1;
                $this->Navigation->params['form']['parent'] = 1;
                $this->Navigation->params['form']['position'] = 1 ;

                $this->Navigation->Session->write('Auth.User', array(
                        'id' => 2,
                        'username' => 'anotheruser',
                        'changepass' => 0,
      			'admin' => 1,
	          ));

                $this->Navigation->admin_reparent();
                $this->assertNotEqual($this->Navigation->error, 'access_denied');
        }

        function testAdminReparentValid() {
                $this->Navigation->RequestHandler = new NavigationControllerMockRequestHandlerComponent();
                $this->Navigation->RequestHandler->setReturnValue('prefers', true);
                $this->Navigation->params['form']['node'] = 2;
                $this->Navigation->params['form']['parent'] = 1;
                $this->Navigation->params['form']['position'] = 3;

                $this->Navigation->Session->write('Auth.User', array(
                        'id' => 2,
                        'username' => 'anotheruser',
                        'changepass' => 0,
                        'admin' => 1,
                  ));

                $this->Navigation->admin_reparent();
                $this->assertNotEqual($this->Navigation->error, 'internal_error');
        }

	function endTest() {
		unset($this->Navigation);
		ClassRegistry::flush();	
	}
}
?>
