<?php
App::import('Controller','Users');
App::import('Component', 'RequestHandler');
App::import('Component', 'FileCmp');
App::import('Component', 'Image');
App::import('Component', 'Recaptcha');

Mock::generatePartial('RequestHandlerComponent', 'UsersControllerMockRequestHandlerComponent', array('prefers'));

Mock::generatePartial('FileCmpComponent', 'UsersControllerMockFileCmpComponent', array(
	'is_uploaded_file',
	'mimetype',
	'save',
	'remove',
	'exists',
));

Mock::generatePartial('ImageComponent', 'UsersControllerMockImageComponent', array(
	'scale',
	'crop',
	'user',
));

Mock::generatePartial('RecaptchaComponent', 'UsersControllerMockRecaptchaComponent', array(
	'valid',
));

class UsersControllerTestUsersController extends UsersController {
	var $name = 'Users';
	var $autoRender = false;

	var $redirectUrl = null;
	var $renderedAction = null;
	var $error = null;
	var $stopped = null;

	var $emailHash = null;
	
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

	function email_passwordrequest($email, $user_id, $hash)
	{
		$this->emailHash = $hash;
	}
}

class UsersControllerTest extends CakeTestCase {
	var $Users = null;
	var $fixtures = array('app.helps', 'app.app_category', 'app.app_data', 'app.application', 'app.app_module', 'app.attachment', 'app.digest', 'app.discussion', 'app.doc', 'app.docs_permission', 'app.docs_tag', 'app.docs_type_data', 'app.docs_type_field', 'app.docs_type', 'app.docs_type_row', 'app.docs_version', 'app.group', 'app.groups_address', 'app.groups_association', 'app.groups_award', 'app.groups_interest', 'app.groups_phone', 'app.groups_projects', 'app.groups_publication', 'app.groups_setting', 'app.groups_url', 'app.groups_users', 'app.inbox', 'app.inbox_hash', 'app.interest', 'app.message_archive', 'app.message', 'app.note', 'app.ontology_concept', 'app.preference', 'app.project', 'app.projects_association', 'app.projects_interest', 'app.projects_setting', 'app.projects_url', 'app.projects_users', 'app.role', 'app.setting', 'app.site_role', 'app.tag', 'app.type', 'app.url', 'app.user', 'app.users_address', 'app.users_association', 'app.users_award', 'app.users_education', 'app.users_interest', 'app.users_job', 'app.users_phone', 'app.users_preference', 'app.users_publication', 'app.users_url', 'app.ldap_user', 'app.word');
	
	function startTest() {
		$this->Users = new UsersControllerTestUsersController();
		$this->Users->constructClasses();
		$this->Users->Component->initialize($this->Users);
		
		$this->Users->Session->write('Auth.User', array(
			'id' => 1,
			'username' => 'testuser',
			'changepass' => 0,
		));
	}

	function testUsersControllerInstance() {
		$this->assertTrue(is_a($this->Users, 'UsersController'));
	}

	function testHome()
	{
		$this->Users->params = Router::parse('users/home/');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->home();
		
		$this->assertEqual($this->Users->redirectUrl, '/users/dashboard');
	}

	function testHomeNotLoggedIn()
	{
		$this->Users->Auth->logout();

		$this->Users->params = Router::parse('users/home/');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->home();
		
		$this->assertEqual($this->Users->redirectUrl, '/users/login');
	}

	/* Not testing index yet. */

	function testDashboardGroups()
	{
		$model = 'groups';

		$this->Users->params = Router::parse('users/dashboard.json');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->RequestHandler = new UsersControllerMockRequestHandlerComponent();
		$this->Users->RequestHandler->setReturnValue('prefers', true);

		$this->Users->params['form']['model'] = $model;
		$this->Users->dashboard();

		$this->assertTrue(isset($this->Users->viewVars['list']));
		$list = $this->Users->viewVars['list'];

		$expected = array(
			'success' => 1,
			'groups' => array(
				array(
					'id' => 3,
					'name' => 'Another Private Test Group',
					'text' => 'Another Private Test Group',
					'leaf' => 'Test',
					'description' => 'Test Group',
					'username' => 'anotherprivatetestgroup',
					'session' => 'group:group_3',
					'token' => 'group:3',
					'type' => 'group',
					'email' => 'anothergrp+private@example.com',
					'privacy' => 'private',
					'image' => '/img/groups/default_small.png',
					'role' => 'group.manager',
					'members' => 5,
					'projects' => 0,
				),
				array(
					'id' => 1,
					'name' => 'Private Test Group',
					'text' => 'Private Test Group',
					'leaf' => 'Test',
					'description' => 'Test Group',
					'username' => 'privatetestgroup',
					'session' => 'group:group_1',
					'token' => 'group:1',
					'type' => 'group',
					'email' => 'testgrp+private@example.com',
					'privacy' => 'private',
					'image' => '/img/groups/default_small.png',
					'role' => 'group.manager',
					'members' => 2,
					'projects' => 1,
				),
			),
		);

		$this->assertEqual($list, $expected);
	}
	
	function testDashboardProjects()
	{
		$model = 'projects';

		$this->Users->params = Router::parse('users/dashboard.json');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->RequestHandler = new UsersControllerMockRequestHandlerComponent();
		$this->Users->RequestHandler->setReturnValue('prefers', true);

		$this->Users->params['form']['model'] = $model;
		$this->Users->dashboard();

		$this->assertTrue(isset($this->Users->viewVars['list']));
		$list = $this->Users->viewVars['list'];
		$this->assertTrue($list['success']);

		$expected = array(
			'success' => 1,
			'projects' => array(
				array(
					'id' => 3,
					'name' => 'Another Private Test Project',
					'text' => 'Another Private Test Project',
					'leaf' => 'Test',
					'description' => 'Another Private Test Project',
					'session' => 'group:project_3',
					'token' => 'project:3',
					'type' => 'project',
					'email' => 'anotherprj+private@example.com',
					'privacy' => 'private',
					'image' => '/img/projects/default_small.png',
					'role' => 'project.manager',
					'members' => 4,
					'group' => 'User: Test User',
					'group_type' => 'user',
					'group_id' => null,
				),
				array(
					'id' => 1,
					'name' => 'Private Test Project',
					'text' => 'Private Test Project',
					'leaf' => 'Test',
					'description' => 'Private Test Project',
					'session' => 'group:project_1',
					'token' => 'project:1',
					'type' => 'project',
					'email' => 'testprj+private@example.com',
					'privacy' => 'private',
					'image' => '/img/projects/default_small.png',
					'role' => 'project.manager',
					'members' => 2,
					'group' => 'Group: Private Test Group',
					'group_type' => 'group',
					'group_id' => 1,
				),
			),
		);

		$this->assertEqual($list, $expected);
	}
	
	function testDashboardNotes()
	{
		$model = 'notes';

		$this->Users->params = Router::parse('users/dashboard.json');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->RequestHandler = new UsersControllerMockRequestHandlerComponent();
		$this->Users->RequestHandler->setReturnValue('prefers', true);

		$this->Users->params['form']['model'] = $model;
		$this->Users->dashboard();

		$this->assertTrue(isset($this->Users->viewVars['list']));
		$list = $this->Users->viewVars['list'];
		$this->assertTrue($list['success']);

		$expected = array(
			'success' => 1,
			'notes' => array(
				array(
					'id' => 2,
					'table_id' => 1,
					'table_type' => 'group',
					'title' => 'Home',
					'created' => $list['notes'][0]['created'],
					'modified' => $list['notes'][0]['modified'],
					'content' => 'Welcome Home',
					'permanent' => 1,
					'group' => 'Group: Private Test Group',
				),
				array(
					'id' => 1,
					'table_id' => 1,
					'table_type' => 'user',
					'title' => 'Home',
					'created' => $list['notes'][1]['created'],
					'modified' => $list['notes'][1]['modified'],
					'content' => 'Welcome Home',
					'permanent' => 1,
					'group' => 'User: Test User',
				),
				array(
					'id' => 3,
					'table_id' => 1,
					'table_type' => 'project',
					'title' => 'Home',
					'created' => $list['notes'][2]['created'],
					'modified' => $list['notes'][2]['modified'],
					'content' => 'Welcome Home',
					'permanent' => 1,
					'group' => 'Project: Private Test Project',
				),
				array(
					'id' => 5,
					'table_id' => 1,
					'table_type' => 'user',
					'title' => 'Second',
					'created' => $list['notes'][3]['created'],
					'modified' => $list['notes'][3]['modified'],
					'content' => 'Second Note',
					'permanent' => 0,
					'group' => 'User: Test User',
				),
			),
		);

		$this->assertEqual($list, $expected);
	}
	
	function testDashboardColleagues()
	{
		$model = 'colleagues';

		$this->Users->params = Router::parse('users/dashboard.json');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->RequestHandler = new UsersControllerMockRequestHandlerComponent();
		$this->Users->RequestHandler->setReturnValue('prefers', true);

		$this->Users->params['form']['model'] = $model;
		$this->Users->dashboard();

		$this->assertTrue(isset($this->Users->viewVars['list']));
		$list = $this->Users->viewVars['list'];
		$this->assertTrue($list['success']);

		$expected = array(
			'success' => 1,
			'colleagues' => array(
				array(
					'id' => 2,
					'name' => 'Another User',
					'username' => 'anotheruser',
					'session' => 'user:anotheruser',
					'token' => 'user:2',
					'type' => 'user',
					'activity' => $list['colleagues'][0]['activity'],
					'group_id' => 0,
					'project_id' => 0,
					'role_id' => 0,
					'role' => 'Unknown',
					'image' => '/img/users/default_small.png',
				),
				array(
					'id' => 4,
					'name' => 'Fourth User',
					'username' => 'fourthuser',
					'session' => 'user:fourthuser',
					'token' => 'user:4',
					'type' => 'user',
					'activity' => $list['colleagues'][1]['activity'],
					'group_id' => 0,
					'project_id' => 0,
					'role_id' => 0,
					'role' => 'Unknown',
					'image' => '/img/users/default_small.png',
				),
/*				array(
					'id' => 3,
					'name' => 'Third User',
					'username' => 'thirduser',
					'session' => 'user:thirduser',
					'token' => 'user:3',
					'type' => 'user',
					'activity' => $list['colleagues'][2]['activity'],
					'group_id' => 0,
					'project_id' => 0,
					'role_id' => 0,
					'role' => 'Unknown',
					'image' => '/img/users/default_small.png',
				),
*/		              array(
                                        'id' => 9090,
                                        'name' => 'Selenium Tester',
                                        'username' => 'selenium1',
                                        'session' => 'user:selenium1',
                                        'token' => 'user:9090',
                                        'type' => 'user',
                                        'activity' => $list['colleagues'][2]['activity'],
                                        'group_id' => 0,
                                        'project_id' => 0,
                                        'role_id' => 0,
                                        'role' => 'Unknown',
                                        'image' => '/img/users/default_small.png',
                                ),

                              array(
                                        'id' => 3,
                                        'name' => 'Third User',
                                        'username' => 'thirduser',
                                        'session' => 'user:thirduser',
                                        'token' => 'user:3',
                                        'type' => 'user',
                                        'activity' => $list['colleagues'][3]['activity'],
                                        'group_id' => 0,
                                        'project_id' => 0,
                                        'role_id' => 0,
                                        'role' => 'Unknown',
                                        'image' => '/img/users/default_small.png',
				),
			),
		);

		$this->assertEqual($list, $expected);
	}
	
	function testDashboardDocuments()
	{
		$model = 'documents';

		$this->Users->params = Router::parse('users/dashboard.json');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->RequestHandler = new UsersControllerMockRequestHandlerComponent();
		$this->Users->RequestHandler->setReturnValue('prefers', true);

		$this->Users->params['form']['model'] = $model;
		$this->Users->dashboard();

		$this->assertTrue(isset($this->Users->viewVars['list']));
		$list = $this->Users->viewVars['list'];

		$expected = array(
			array(
				'id' => 'root-group-3',  //'id' => 'root-user-1',
				'parent_id' => null,
				'title' => 'Group: Another Private Test Group',
				'text' => 'Group: Another Private Test Group',
				'author' => null,
				'status' => null,
				'size' => null,
				'description' => null,
				'created' => $list[0]['created'],
				'version' => null,
				'version_id' => null,
				'uiProvider' => 'col',
				'cls' => 'root',
				'iconCls' => 'doc-root',
				'table_type' => 'group',
				'table_id' => 3,
				'shared' => 0,
				'expandable' => 1,
				'leaf' => null,
				'draggable' => null,
			),
			array(
				'id' => 'root-group-1',
				'parent_id' => null,
				'title' => 'Group: Private Test Group',
				'text' => 'Group: Private Test Group',
				'author' => null,
				'status' => null,
				'size' => null,
				'description' => null,
				'created' => $list[1]['created'],
				'version' => null,
				'version_id' => null,
				'uiProvider' => 'col',
				'cls' => 'root',
				'iconCls' => 'doc-root',
				'table_type' => 'group',
				'table_id' => 1,
				'shared' => 0,
				'expandable' => 1,
				'leaf' => null,
				'draggable' => null,
			),
			array(
				'id' => 'root-project-3',
				'parent_id' => null,
				'title' => 'Project: Another Private Test Project',
				'text' => 'Project: Another Private Test Project',
				'author' => null,
				'status' => null,
				'size' => null,
				'description' => null,
				'created' => $list[2]['created'],
				'version' => null,
				'version_id' => null,
				'uiProvider' => 'col',
				'cls' => 'root',
				'iconCls' => 'doc-root',
				'table_type' => 'project',
				'table_id' => 3,
				'shared' => 0,
				'expandable' => 1,
				'leaf' => null,
				'draggable' => null,
			),
			/*array(
				'id' => 'root-project-3',
				'parent_id' => null,
				'title' => 'Project: Another Private Test Project',
				'text' => 'Project: Another Private Test Project',
				'author' => null,
				'status' => null,
				'size' => null,
				'description' => null,
				'created' => $list[3]['created'],
				'version' => null,
				'uiProvider' => 'col',
				'cls' => 'root',
				'iconCls' => 'doc-root',
				'table_type' => 'project',
				'table_id' => 3,
				'shared' => 0,
				'expandable' => 1,
				'leaf' => null,
				'draggable' => null,
			), */
			array(
				'id' => 'root-project-1',
				'parent_id' => null,
				'title' => 'Project: Private Test Project',
				'text' => 'Project: Private Test Project',
				'author' => null,
				'status' => null,
				'size' => null,
				'description' => null,
				'created' => $list[3]['created'],
				'version' => null,
				'version_id' => null,
				'uiProvider' => 'col',
				'cls' => 'root',
				'iconCls' => 'doc-root',
				'table_type' => 'project',
				'table_id' => 1,
				'shared' => 0,
				'expandable' => 1,
				'leaf' => null,
				'draggable' => null,
			)
		);

		$this->assertEqual($list, $expected);
	}

	function testDashboardDocumentsInvalidParentId()
	{
		$model = 'documents';
		$doc_id = 9000;

		$this->Users->params = Router::parse('users/dashboard.json');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->RequestHandler = new UsersControllerMockRequestHandlerComponent();
		$this->Users->RequestHandler->setReturnValue('prefers', true);

		$this->Users->params['form']['model'] = $model;
		$this->Users->params['form']['node'] = $doc_id;
		$this->Users->dashboard();

		$this->assertEqual($this->Users->error, 'invalid_field');
	}
	
	function testDashboardUrls()
	{
		$model = 'url';

		$this->Users->params = Router::parse('users/dashboard.json');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->RequestHandler = new UsersControllerMockRequestHandlerComponent();
		$this->Users->RequestHandler->setReturnValue('prefers', true);

		$this->Users->params['form']['model'] = $model;
		$this->Users->dashboard();

		$this->assertTrue(isset($this->Users->viewVars['list']));
		$list = $this->Users->viewVars['list'];
		$this->assertTrue($list['success']);

		$expected = array(
			'success' => 1,
			'urls' => array(
				array(
					'id' => 3,
					'table_type' => 'project',
					'table_id' => 1,
					'link' => 'http://example.com',
					'label' => 'Test',
					'description' => 'Test',
					'privacy' => 0,
					'group' => 'Project: Private Test Project',
				),
				array(
					'id' => 2,
					'table_type' => 'group',
					'table_id' => 1,
					'link' => 'http://example.com',
					'label' => 'Test',
					'description' => 'Test',
					'privacy' => 0,
					'group' => 'Group: Private Test Group',
				),
				array(
					'id' => 1,
					'table_type' => 'user',
					'table_id' => 1,
					'link' => 'http://example.com',
					'label' => 'Test',
					'description' => 'Test',
					'privacy' => 0,
					'group' => 'User: Test User',
				),
			),
		);

		$this->assertEqual($list, $expected);
	}

	function testDashboardApps()
	{
		$model = 'app';
		$app_id = 1;

		$this->Users->params = Router::parse('users/dashboard.json');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->RequestHandler = new UsersControllerMockRequestHandlerComponent();
		$this->Users->RequestHandler->setReturnValue('prefers', true);

		$this->Users->params['form']['model'] = $model;
		$this->Users->params['form']['id'] = $app_id;
		$this->Users->dashboard();

		$this->assertEqual($this->Users->redirectUrl, '/apps/iframe/home/' . $app_id . '/user/' . $this->Users->Session->read('Auth.User.id') . '.json');
	}

	function testDashboardInvalidModel()
	{
		$model = 'invalid';

		$this->Users->params = Router::parse('users/dashboard.json');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->RequestHandler = new UsersControllerMockRequestHandlerComponent();
		$this->Users->RequestHandler->setReturnValue('prefers', true);

		$this->Users->params['form']['model'] = $model;
		$this->Users->dashboard();

		$this->assertEqual($this->Users->error, 'invalid_field');
	}

	function testProfileNullUserId()
	{
		$user_id = null;

		$this->Users->params = Router::parse('users/profile/' . $user_id);
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->profile($user_id);

		$this->assertEqual($this->Users->redirectUrl, '/users/profile/' . $this->Users->Session->read('Auth.User.id'));
	}

	function testProfileInvalidUserId()
	{
		$user_id = 'invalid';

		$this->Users->params = Router::parse('users/profile/' . $user_id);
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->profile($user_id);

		$this->assertEqual($this->Users->error, 'invalid_field');
	}

	function testProfileInvalidUserIdNotFound()
	{
		$user_id = 9000;

		$this->Users->params = Router::parse('users/profile/' . $user_id);
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->profile($user_id);

		$this->assertEqual($this->Users->error, 'invalid_field');
	}

	function testProfileAccessDenied()
	{
		$user_id = 5;

		$this->Users->params = Router::parse('users/profile/' . $user_id);
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->profile($user_id);

		$this->assertEqual($this->Users->error, 'access_denied');
	}

	function testAccount()
	{
		$this->Users->data = array(
			'User' => array(
				'email' => 'edited-email@example.com',
				'interests' => 'Edited Interest 1',
				'prefix'  => 'Edited',
				'first_name'  => 'First',
				'last_name'  => 'Last',
				'name'  => 'First Last',
				'suffix'  => 'Edited',
				'title'  => 'Edited',
				'description'  => 'Edited',
				'status'  => 'Edited',
				'gender' => 'male',
				'picture' => array(
					'name' => null,
					'type' => null,
					'tmp_name' => null,
					'error' => 4,
					'size' => 0,
				),
			),
			'UsersAddress' => array(
				array(
					'label' => 'Edited',
					'address1' => '212 W. 10th St',
					'address2' => 'Suite A470',
					'city' => 'Indianapolis',
					'state' => 'IN',
					'zip_code' => '46202',
					'country' => 'USA',
				),
			),
			'UsersPhone' => array(
				array(
					'label' => 'Edited',
					'phone_number' => '1-317-489-4173',	
				),
			),
			'UsersUrl' => array(
				array(
					'label' => 'Edited',
					'link' => 'http://edited.example.com',
				),
			),
			'UsersEducation' => array(
				array(
					'label' => 'Edited',
					'institution' => 'Edited Institution',
					'years' => '2010',
					'degree' => 'Edited Degree',
				),
			),
			'UsersAssociation' => array(
				array(
					'label' => 'Edited',
					'association' => 'Edited Association',
					'role' => 'Editor',
				),
			),
			'UsersAward' => array(
				array(
					'label' => 'Edited',
					'award' => 'Edited Award',
				),
			),
		);

		$this->Users->params = Router::parse('users/account');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->FileCmp = new UsersControllerMockFileCmpComponent();
		$this->Users->FileCmp->setReturnValue('is_uploaded_file', true);
		$this->Users->FileCmp->setReturnValue('mimetype', 'image/png');
		$this->Users->FileCmp->setReturnValue('save', 100);
		$this->Users->FileCmp->setReturnValue('remove', true);
		$this->Users->FileCmp->setReturnValue('exists', false);

		$this->Users->Image = new UsersControllerMockImageComponent();
		$this->Users->Image->setReturnValue('scale', 'picture data');
		$this->Users->Image->setReturnValue('crop', 'picture data');
		$this->Users->Image->setReturnValue('user', 'edited');

		$this->Users->account();

		//Check User
		$conditions = array(
			'User.id' => $this->Users->Session->read('Auth.User.id'),
		);
		$this->Users->User->recursive = -1;
		$result = $this->Users->User->find('first', array('conditions' => $conditions));

		$expected = array(
			'User' => array(
				'id'  => $result['User']['id'],
				'username'  => 'testuser',
				'password'  => $result['User']['password'],
				'email'  => 'edited-email@example.com',
				'prefix'  => 'Edited',
				'first_name'  => 'First',
				'last_name'  => 'Last',
				'name'  => 'First Last',
				'suffix'  => 'Edited',
				'title'  => 'Edited',
				'description'  => 'Edited',
				'status'  => 'Edited',
				'gender' => 'male',
				'age'  => 50,
				'picture'  => 'edited',
				'privacy' => 'private',
				'activity'  => $result['User']['activity'],
				'registered'  => $result['User']['registered'],
				'hash'  => $result['User']['hash'],
				'private_hash'  => $result['User']['private_hash'],
				'auth_token'  => 'AAAAA',
				'auth_timestamp'  => 1269625040,
				'confirmed'  => 0,
				'changepass'  => 0,
				'security_question'  => 1,
				'security_answer'  => 'hash',
				'language_id'  => 1,
				'timezone_id'  => 1,
				'ip'  => $result['User']['ip'],
				'admin'  => 0,
				'type' => 'user',
				'vivo'  => null 
			),
		);
		$this->assertEqual($result, $expected);

		//Check UsersAddress
		$conditions = array(
			'UsersAddress.user_id' => $this->Users->Session->read('Auth.User.id'),
		);
		$this->Users->UsersAddress->recursive = -1;
		$result = $this->Users->UsersAddress->find('first', array('conditions' => $conditions));

		$expected = array(
			'UsersAddress' => array(
				'id' => $result['UsersAddress']['id'],
				'user_id' => $this->Users->Session->read('Auth.User.id'),
				'address1' => '212 W. 10th St',
				'address2' => 'Suite A470',
				'city' => 'Indianapolis',
				'state' => 'IN',
				'country' => 'USA',
				'zip_code' => '46202',
				'longitude' => $result['UsersAddress']['longitude'],
				'latitude' => $result['UsersAddress']['latitude'],
				'label' => 'Edited',
				'privacy' => 0,
			),
		);

		$this->assertEqual($result, $expected);

		//Check UsersPhone
		$conditions = array(
			'UsersPhone.user_id' => $this->Users->Session->read('Auth.User.id'),
		);
		$this->Users->UsersPhone->recursive = -1;
		$result = $this->Users->UsersPhone->find('first', array('conditions' => $conditions));

		$expected = array(
			'UsersPhone' => array(
				'id' => $result['UsersPhone']['id'],
				'user_id' => $this->Users->Session->read('Auth.User.id'),
				'phone_number' => '1-317-489-4173',
				'label' => 'Edited',
				'privacy' => 0,
			),
		);
		$this->assertEqual($result, $expected);

		//Check UsersUrl
		$conditions = array(
			'UsersUrl.user_id' => $this->Users->Session->read('Auth.User.id'),
		);
		$this->Users->UsersUrl->recursive = -1;
		$result = $this->Users->UsersUrl->find('first', array('conditions' => $conditions));

		$expected = array(
			'UsersUrl' => array(
				'id' => $result['UsersUrl']['id'],
				'user_id' => $this->Users->Session->read('Auth.User.id'),
				'link' => 'http://edited.example.com',
				'label' => 'Edited',
				'privacy' => 0,
			),
		);
		$this->assertEqual($result, $expected);

		//Check UsersEducation
		$conditions = array(
			'UsersEducation.user_id' => $this->Users->Session->read('Auth.User.id'),
		);
		$this->Users->UsersEducation->recursive = -1;
		$result = $this->Users->UsersEducation->find('first', array('conditions' => $conditions));

		$expected = array(
			'UsersEducation' => array(
				'id' => $result['UsersEducation']['id'],
				'user_id' => $this->Users->Session->read('Auth.User.id'),
				'label' => 'Edited',
				'institution' => 'Edited Institution',
				'years' => '2010',
				'degree' => 'Edited Degree',
				'privacy' => 0,
			),
		);
		$this->assertEqual($result, $expected);

		//Check UsersAssociation
		$conditions = array(
			'UsersAssociation.user_id' => $this->Users->Session->read('Auth.User.id'),
		);
		$this->Users->UsersAssociation->recursive = -1;
		$result = $this->Users->UsersAssociation->find('first', array('conditions' => $conditions));

		$expected = array(
			'UsersAssociation' => array(
				'id' => $result['UsersAssociation']['id'],
				'user_id' => $this->Users->Session->read('Auth.User.id'),
				'label' => 'Edited',
				'association' => 'Edited Association',
				'role' => 'Editor',
				'privacy' => 0,
			),
		);
		$this->assertEqual($result, $expected);

		//Check UsersAward
		$conditions = array(
			'UsersAward.user_id' => $this->Users->Session->read('Auth.User.id'),
		);
		$this->Users->UsersAward->recursive = -1;
		$result = $this->Users->UsersAward->find('first', array('conditions' => $conditions));

		$expected = array(
			'UsersAward' => array(
				'id' => $result['UsersAward']['id'],
				'user_id' => $this->Users->Session->read('Auth.User.id'),
				'label' => 'Edited',
				'award' => 'Edited Award',
				'privacy' => null,
			),
		);
		$this->assertEqual($result, $expected);

		//Check Interest
		$conditions = array(
			'Interest.keyword' => 'editedinterest1',
		);
		$this->Users->Interest->recursive = -1;
		$result = $this->Users->Interest->find('first', array('conditions' => $conditions));

		$expected = array(
			'Interest' => array(
				'id' => $result['Interest']['id'],
				'keyword' => 'editedinterest1',
				'name' => 'Edited Interest 1',
			),
		);
//		$this->assertEqual($result, $expected);

		$interest_id = $result['Interest']['id'];

		//Check UsersInterest
		$conditions = array(
			'UsersInterest.user_id' => $this->Users->Session->read('Auth.User.id'),
		);
		$this->Users->UsersInterest->recursive = -1;
		$result = $this->Users->UsersInterest->find('first', array('conditions' => $conditions));

		$expected = array(
			'UsersInterest' => array(
				'id' => '2',
				'user_id' => $this->Users->Session->read('Auth.User.id'),
				'interest_id' => '2',
			),
		);
		$this->assertEqual($result, $expected);

		//Check Doc Roots
		$conditions = array(
			'Doc.table_type' => 'user',
			'Doc.table_id' => $this->Users->Session->read('Auth.User.id'),
			'Doc.parent_id' => null,
			'Doc.type' => 'folder',
		);
		$this->Users->Doc->recursive = -1;
		$results = $this->Users->Doc->find('all', array('conditions' => $conditions));

		$expected = array(
			array(
				'Doc' => array(
					'id' => $results[0]['Doc']['id'],
					'table_type' => 'user',
					'table_id' => 1,
					'parent_id' => null,
					'lft' => 1,
					'rght' => 6,
					'filename' => null,
					'title' => 'First Last - Private',
					'name' => null,
					'path' => '/First Last - Private',
					'author_id' => 1,
					'description' => null,
					'created' => $results[0]['Doc']['created'],
					'modified' => $results[0]['Doc']['modified'],
					'status' => 'in',
					'current_user_id' => null,
					'shared' => 0,
					'type' => 'folder'
				),
			),
			array(
				'Doc' => array(
					'id' => $results[1]['Doc']['id'],
					'table_type' => 'user',
					'table_id' => 1,
					'parent_id' => null,
					'lft' => 1,
					'rght' => 4,
					'filename' => null,
					'title' => 'First Last - Public',
					'name' => '',
					'path' => '/First Last - Public',
					'author_id' => 1,
					'description' => '',
					'created' => $results[1]['Doc']['created'],
					'modified' => $results[1]['Doc']['modified'],
					'status' => 'in',
					'current_user_id' => null,
					'shared' => 1,
					'type' => 'folder'
				),
			),
		);
		$this->assertEqual($results, $expected);

		//Check redirect
		$this->assertEqual($this->Users->redirectUrl, '/users/account');
	}
/*
	function testAccountJson()
	{
		$this->Users->params = Router::parse('users/account.json');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->RequestHandler = new UsersControllerMockRequestHandlerComponent();
		$this->Users->RequestHandler->setReturnValue('prefers', true);

		$this->Users->account();

		$this->assertTrue(isset($this->Users->viewVars['node']));
		$node = $this->Users->viewVars['node'];

		$expected = array(
			'User' => array(
				'id' => $node['User']['id'],
				'email'  => 'testuser@example.com',
				'alt_email'  => 'testtest@example.com',
				'prefix'  => 'Mr.',
				'username'  => 'testuser',
				'name'  => 'Test User',
				'suffix'  => 'Esq.',
				'title'  => 'Programmer',
				'description'  => 'test',
				'age'  => 50,
				'gender' => 'male',
				'privacy' => 'private',
				'language_id'  => 1,
				'timezone_id'  => 1,
				'interests' => 'Test Test',
			),
			'UsersPhone' => array(
				array(
					'id' => 1,
					'user_id' => 1,
					'id' => $node['UsersPhone'][0]['id'],
					'user_id' => $node['UsersPhone'][0]['user_id'],
					'phone_number' => '1-317-489-6818',
					'label' => 'Test',
					'privacy' => 0,
				),
			),
			'UsersEducation' => array(
				array(
					'id' => 1,
					'user_id' => 1,
					'id' => $node['UsersEducation'][0]['id'],
					'user_id' => $node['UsersEducation'][0]['user_id'],
					'label' => 'Test',
					'institution' => 'Test',
					'years' => 2010,
					'degree' => 'Test',
					'privacy' => 0,
				),
			),
			'UsersAssociation' => array(
				array(
					'id' => 1,
					'user_id' => 1,
					'id' => $node['UsersAssociation'][0]['id'],
					'user_id' => $node['UsersAssociation'][0]['user_id'],
					'label' => 'Test',
					'association' => 'Test',
					'role' => 'Test',
					'privacy' => 1,
				),
			),
			'UsersAward' => array(
				array(
					'id' => $node['UsersAward'][0]['id'],
					'user_id' => $node['UsersAward'][0]['user_id'],
					'label' => 'Test',
					'award' => 'Test',
					'privacy' => 1,
				),
			),
			'UsersAddress' => array(
				array(
					'id' => $node['UsersAddress'][0]['id'],
					'user_id' => $node['UsersAddress'][0]['user_id'],
					'address1' => '123 Test',
					'address2' => 'Test',
					'city' => 'Test',
					'state' => 'IN',
					'country' => 'USA',
					'zip_code' => 'Test',
					'longitude' => $node['UsersAddress'][0]['longitude'],
					'latitude' => $node['UsersAddress'][0]['latitude'],
					'label' => 'Test',
					'privacy' => 1,
				),
			),
			'UsersUrl' => array(
				array(
					'id' => $node['UsersUrl'][0]['id'],
					'user_id' => $node['UsersUrl'][0]['user_id'],
					'link' => 'http://example.com',
					'label' => 'Test',
					'privacy' => 0,
				),
			),
			'Interest' => array(
				array(
					'id' => $node['Interest'][0]['id'],
					'keyword' => 'testtest',
					'name' => 'Test Test',
					'UsersInterest' => $node['Interest'][0]['UsersInterest'],
				),
			),
		
		);

		$this->assertEqual($node, $expected);
	}
*/
	function testAccountInvalidUser()
	{
		try {
			$this->Users->Session->write('Auth.User', array(
				'id' => 9000,
				'username' => 'invaliduser',
				'changepass' => 0,
			));

			$this->Users->params = Router::parse('users/account.json');
			$this->Users->beforeFilter();
			$this->Users->Component->startup($this->Users);

			$this->Users->RequestHandler = new UsersControllerMockRequestHandlerComponent();
			$this->Users->RequestHandler->setReturnValue('prefers', true);

			$this->Users->account();
	
			$this->assertEqual($this->Users->error, 'internal_error');
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}
	}

	function testAccountInvalidDataEmail()
	{
		$this->Users->data = array(
			'User' => array(
				'email' => 'invalid',
				'alt_email' => 'edited-alt-email@example.com',
				'interests' => 'Edited Interest 1',
				'prefix'  => 'Edited',
				'first_name'  => 'First',
				'last_name'  => 'Last',
				'name'  => 'First Last',
				'suffix'  => 'Edited',
				'title'  => 'Edited',
				'description'  => 'Edited',
				'status'  => 'Edited',
				'gender' => 'male',
				'picture' => array(
					'name' => null,
					'type' => null,
					'tmp_name' => null,
					'error' => 4,
					'size' => 0,
				),
			),
		);

		$this->Users->params = Router::parse('users/account');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->account();

		$this->assertEqual($this->Users->error, 'invalid_field');
	}

	function testAccountInvalidDataAltEmail()
	{
		$this->Users->data = array(
			'User' => array(
				'email' => 'edited-email@example.com',
				'alt_email' => 'invalid',
				'interests' => 'Edited Interest 1',
				'prefix'  => 'Edited',
				'first_name'  => 'First',
				'last_name'  => 'Last',
				'name'  => 'First Last',
				'suffix'  => 'Edited',
				'title'  => 'Edited',
				'description'  => 'Edited',
				'status'  => 'Edited',
				'gender' => 'male',
				'picture' => array(
					'name' => null,
					'type' => null,
					'tmp_name' => null,
					'error' => 4,
					'size' => 0,
				),
			),
		);

		$this->Users->params = Router::parse('users/account');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->account();

		$this->assertEqual($this->Users->error, 'invalid_field');
	}

	function testAccountInvalidDataUsersAddress()
	{
		$this->Users->data = array(
			'User' => array(
				'email' => 'edited-email@example.com',
				'alt_email' => 'edited-alt-email@example.com',
				'interests' => 'Edited Interest 1',
				'prefix'  => 'Edited',
				'first_name'  => 'First',
				'last_name'  => 'Last',
				'name'  => 'First Last',
				'suffix'  => 'Edited',
				'title'  => 'Edited',
				'description'  => 'Edited',
				'status'  => 'Edited',
				'gender' => 'male',
				'picture' => array(
					'name' => null,
					'type' => null,
					'tmp_name' => null,
					'error' => 4,
					'size' => 0,
				),
			),
			'UsersAddress' => array(
				array(
					'label' => null,
					'address1' => '212 W. 10th St',
					'address2' => 'Suite A470',
					'city' => 'Indianapolis',
					'state' => 'IN',
					'zip_code' => '46202',
					'country' => 'USA',
				),
			),
		);

		$this->Users->params = Router::parse('users/account');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->account();

		$this->assertEqual($this->Users->error, 'invalid_field');
	}

	function testAccountInvalidDataUsersPhone()
	{
		$this->Users->data = array(
			'User' => array(
				'email' => 'edited-email@example.com',
				'alt_email' => 'edited-alt-email@example.com',
				'interests' => 'Edited Interest 1',
				'prefix'  => 'Edited',
				'first_name'  => 'First',
				'last_name'  => 'Last',
				'name'  => 'First Last',
				'suffix'  => 'Edited',
				'title'  => 'Edited',
				'description'  => 'Edited',
				'status'  => 'Edited',
				'gender' => 'male',
				'picture' => array(
					'name' => null,
					'type' => null,
					'tmp_name' => null,
					'error' => 4,
					'size' => 0,
				),
			),
			'UsersPhone' => array(
				array(
					'label' => null,
					'phone_number' => '1-317-489-4173',	
				),
			),
		);

		$this->Users->params = Router::parse('users/account');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->account();

		$this->assertEqual($this->Users->error, 'invalid_field');
	}

	function testAccountInvalidDataUsersUrl()
	{
		$this->Users->data = array(
			'User' => array(
				'email' => 'edited-email@example.com',
				'alt_email' => 'edited-alt-email@example.com',
				'interests' => 'Edited Interest 1',
				'prefix'  => 'Edited',
				'first_name'  => 'First',
				'last_name'  => 'Last',
				'name'  => 'First Last',
				'suffix'  => 'Edited',
				'title'  => 'Edited',
				'description'  => 'Edited',
				'status'  => 'Edited',
				'gender' => 'male',
				'picture' => array(
					'name' => null,
					'type' => null,
					'tmp_name' => null,
					'error' => 4,
					'size' => 0,
				),
			),
			'UsersUrl' => array(
				array(
					'label' => null,
					'link' => 'http://edited.example.com',
				),
			),
		);

		$this->Users->params = Router::parse('users/account');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->account();

		$this->assertEqual($this->Users->error, 'invalid_field');
	}

	function testAccountInvalidDataUsersEducation()
	{
		$this->Users->data = array(
			'User' => array(
				'email' => 'edited-email@example.com',
				'alt_email' => 'edited-alt-email@example.com',
				'interests' => 'Edited Interest 1',
				'prefix'  => 'Edited',
				'first_name'  => 'First',
				'last_name'  => 'Last',
				'name'  => 'First Last',
				'suffix'  => 'Edited',
				'title'  => 'Edited',
				'description'  => 'Edited',
				'status'  => 'Edited',
				'gender' => 'male',
				'picture' => array(
					'name' => null,
					'type' => null,
					'tmp_name' => null,
					'error' => 4,
					'size' => 0,
				),
			),
			'UsersEducation' => array(
				array(
					'label' => null,
					'institution' => 'Edited Institution',
					'years' => '2010',
					'degree' => 'Edited Degree',
				),
			),
		);

		$this->Users->params = Router::parse('users/account');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->account();

		$this->assertEqual($this->Users->error, 'invalid_field');
	}

	function testAccountInvalidDataUsersAssociation()
	{
		$this->Users->data = array(
			'User' => array(
				'email' => 'edited-email@example.com',
				'alt_email' => 'edited-alt-email@example.com',
				'interests' => 'Edited Interest 1',
				'prefix'  => 'Edited',
				'first_name'  => 'First',
				'last_name'  => 'Last',
				'name'  => 'First Last',
				'suffix'  => 'Edited',
				'title'  => 'Edited',
				'description'  => 'Edited',
				'status'  => 'Edited',
				'gender' => 'male',
				'picture' => array(
					'name' => null,
					'type' => null,
					'tmp_name' => null,
					'error' => 4,
					'size' => 0,
				),
			),
			'UsersAssociation' => array(
				array(
					'label' => null,
					'association' => 'Edited Association',
					'role' => 'Editor',
				),
			),
		);

		$this->Users->params = Router::parse('users/account');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->account();

		$this->assertEqual($this->Users->error, 'invalid_field');
	}

	function testAccountInvalidDataUsersAward()
	{
		$this->Users->data = array(
			'User' => array(
				'email' => 'edited-email@example.com',
				'alt_email' => 'edited-alt-email@example.com',
				'interests' => 'Edited Interest 1',
				'prefix'  => 'Edited',
				'first_name'  => 'First',
				'last_name'  => 'Last',
				'name'  => 'First Last',
				'suffix'  => 'Edited',
				'title'  => 'Edited',
				'description'  => 'Edited',
				'status'  => 'Edited',
				'gender' => 'male',
				'picture' => array(
					'name' => null,
					'type' => null,
					'tmp_name' => null,
					'error' => 4,
					'size' => 0,
				),
			),
			'UsersAward' => array(
				array(
					'label' => null,
					'award' => 'Edited Award',
				),
			),
		);

		$this->Users->params = Router::parse('users/account');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->account();

		$this->assertEqual($this->Users->error, 'invalid_field');
	}

	function testAccountInvalidDataUsersInterest()
	{
		$this->Users->data = array(
			'User' => array(
				'email' => 'edited-email@example.com',
				'alt_email' => 'edited-alt-email@example.com',
				'interests' => 'Edited Interest 1',
				'prefix'  => 'Edited',
				'first_name'  => 'First',
				'last_name'  => 'Last',
				'name'  => 'First Last',
				'suffix'  => 'Edited',
				'title'  => 'Edited',
				'description'  => 'Edited',
				'status'  => 'Edited',
				'gender' => 'male',
				'picture' => array(
					'name' => null,
					'type' => null,
					'tmp_name' => null,
					'error' => 4,
					'size' => 0,
				),
				'interests' => '"',
			),
		);

		$this->Users->params = Router::parse('users/account');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->account();

		$conditions = array(
			'UsersInterest.user_id' => $this->Users->Session->read('Auth.User.id'),
		);

		$results = $this->Users->UsersInterest->find('all', array('conditions' => $conditions));
		$this->assertTrue(empty($results));
	}

	function testAccountInvalidDataUser()
	{
		$this->Users->data = array(
			'User' => array(
				'email' => 'edited-email@example.com',
				'alt_email' => 'edited-alt-email@example.com',
				'interests' => 'Edited Interest 1',
				'prefix'  => 'Edited',
				'first_name'  => 'First',
				'last_name'  => 'Last',
				'name'  => null,
				'suffix'  => 'Edited',
				'title'  => 'Edited',
				'description'  => 'Edited',
				'status'  => 'Edited',
				'gender' => 'male',
			),
		);

		$this->Users->params = Router::parse('users/account');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->account();

		$this->assertEqual($this->Users->error, 'invalid_field');
	}

	/*
	 * TODO: We are skipping the VIVO function because
	 * the current code was used for a tech demo
	 * and it will surely change
	function testVivo()
	{
	}
	 */

	function testLogin()
	{
		$this->Users->Auth->logout();
		$this->assertFalse($this->Users->Session->check('Auth.User'));

		$this->Users->data = array(
			'User' => array(
				'username' => 'testuser',
				'passwd' => 'test',
			),
		);

		$this->Users->params = Router::parse('users/login');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->login();

		$this->assertTrue($this->Users->Session->check('Auth.User'));

		$this->assertEqual($this->Users->redirectUrl, $this->Users->Auth->redirect());
	}

	function testLoginUrl()
	{
		$url = '/test/login';
		$base64 = base64_encode($url);

		$this->Users->Auth->logout();
		$this->assertFalse($this->Users->Session->check('Auth.User'));

		$this->Users->data = array(
			'User' => array(
				'username' => 'testuser',
				'passwd' => 'test',
			),
		);

		$this->Users->params = Router::parse('users/login/' . $base64);
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->login($base64);

		$this->assertEqual($this->Users->redirectUrl, $url);
	}

	function testLoginUrlXml()
	{
		$url = '/test/login.xml';
		$base64 = base64_encode($url);

		$this->Users->data = array(
			'User' => array(
				'username' => 'testuser',
				'passwd' => 'test',
			),
		);

		$this->Users->Auth->logout();
		$this->assertFalse($this->Users->Session->check('Auth.User'));

		$this->Users->params = Router::parse('users/login/' . $base64);
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->login($base64);

		$this->assertEqual($this->Users->redirectUrl, '/');
	}

	function testLoginPassedCookie()
	{
		$this->Users->Auth->logout();
		$this->assertFalse($this->Users->Session->check('Auth.User'));

		$this->Users->Cookie->write('Auth.User', array(
			'username' => 'testuser',
			'passwd' => 'test',
		));

		$this->Users->params = Router::parse('users/login');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->login();

		$this->assertTrue($this->Users->Session->check('Auth.User'));

		$this->assertEqual($this->Users->redirectUrl, $this->Users->Auth->redirect());
	}

	function testLoginSavedCookie()
	{
		$this->Users->Auth->logout();
		$this->assertFalse($this->Users->Session->check('Auth.User'));

		$this->Users->data = array(
			'User' => array(
				'username' => 'testuser',
				'passwd' => 'test',
				'remember' => 1,
			),
		);

		$this->Users->params = Router::parse('users/login');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->login();

		$this->assertTrue($this->Users->Session->check('Auth.User'));

		$cookie = $this->Users->Cookie->read('Auth.User');
		$this->assertFalse(empty($cookie));

		$this->assertEqual($cookie['username'], $this->Users->data['User']['username']);
		$this->assertEqual($cookie['passwd'], $this->Users->data['User']['passwd']);

		$this->assertEqual($this->Users->redirectUrl, $this->Users->Auth->redirect());
	}
/*
	function testLoginChangepass()
	{
		$this->Users->Auth->logout();
		$this->assertFalse($this->Users->Session->check('Auth.User'));

		$this->Users->User->id = 9090;
		$this->assertTrue($this->Users->User->saveField('changepass', 1));

		$this->Users->changepass();

		$this->Users->data = array(
			'User' => array(
				'username' => 'selenium1',
				'passwd' => '11111111',
			),
		);

		$this->Users->params = Router::parse('users/login');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->login();

		//$this->assertTrue($this->Users->Session->check('Auth.User'));

		$this->assertEqual($this->Users->Auth->redirect(), '/users/changepass');
	}
*/
	function testLogout()
	{
		$this->Users->params = Router::parse('users/logout');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->logout();

		$this->assertFalse($this->Users->Session->check('Auth.User'));

		$cookie = $this->Users->Cookie->read('Auth.User');
		$this->assertTrue(empty($cookie));

		$this->assertEqual($this->Users->redirectUrl, '/users/login');
	}

	function testRegister()
	{
		$this->Users->data = array(
			'User' => array(
				'name' => 'New User',
				'email' => 'newuser@example.com',
				'username' => 'newuser',
				'password' => $this->Users->Auth->password('newuser'),
				'password2' => 'newuser',
			),
		);	

		$this->Users->params = Router::parse('users/register.json');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->RequestHandler = new UsersControllerMockRequestHandlerComponent();
		$this->Users->RequestHandler->setReturnValue('prefers', true);

		$this->Users->Recaptcha = new UsersControllerMockRecaptchaComponent();
		$this->Users->Recaptcha->setReturnValue('valid', true);

		$this->Users->params['form'] = array();
		$this->Users->register();

		$this->assertTrue(isset($this->Users->viewVars['response']));
		$response = $this->Users->viewVars['response'];
		$this->assertTrue($response['success']);

		$conditions = array(
			'User.username' => $this->Users->data['User']['username'],
		);
		$this->Users->User->recursive = -1;
		$result = $this->Users->User->find('first', array('conditions' => $conditions));
		$this->assertFalse(empty($result));

		$expected = array(
			'User' => array(
				'id'  => $result['User']['id'],
				'username'  => 'newuser',
				'password'  => $result['User']['password'],
				'email'  => 'newuser@example.com',
				'prefix'  => null,
				'first_name'  => null,
				'last_name'  => null,
				'name'  => 'New User',
				'suffix'  => null,
				'title'  => null,
				'description'  => null,
				'status'  => null,
				'gender' => 'unspecified',
				'age'  => null,
				'picture'  => null,
				'privacy' => 'private',
				'activity'  => $result['User']['activity'],
				'registered'  => $result['User']['registered'],
				'hash'  => $result['User']['hash'],
				'private_hash'  => $result['User']['private_hash'],
				'auth_token'  => null,
				'auth_timestamp'  => 0,
				'confirmed'  => 0,
				'changepass'  => 0,
				'security_question'  => 0,
				'security_answer'  => null,
				'language_id'  => 1,
				'timezone_id'  => 39,
				'ip'  => $result['User']['ip'],
				'admin'  => 0,
				'type' => 'user',
				'vivo'  => null 
			),
		);
		$this->assertEqual($result, $expected);
	}

	function testRegisterBadRecaptcha()
	{
		$this->Users->data = array(
			'User' => array(
				'name' => 'New User',
				'email' => 'newuser@example.com',
				'username' => 'newuser',
				'password' => $this->Users->Auth->password('newuser'),
				'password2' => 'newuser',
			),
		);	

		$this->Users->params = Router::parse('users/register.json');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->RequestHandler = new UsersControllerMockRequestHandlerComponent();
		$this->Users->RequestHandler->setReturnValue('prefers', true);

		$this->Users->Recaptcha = new UsersControllerMockRecaptchaComponent();
		$this->Users->Recaptcha->setReturnValue('valid', false);

		$this->Users->params['form'] = array();
		$this->Users->register();

		$this->assertTrue(isset($this->Users->viewVars['response']));
		$response = $this->Users->viewVars['response'];
		$this->assertFalse($response['success']);
	}

	function testRegisterBadPassword()
	{
		$this->Users->data = array(
			'User' => array(
				'name' => 'New User',
				'email' => 'newuser@example.com',
				'username' => 'newuser',
				'password' => $this->Users->Auth->password('newuser'),
				'password2' => 'nomatch',
			),
		);	

		$this->Users->params = Router::parse('users/register.json');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->RequestHandler = new UsersControllerMockRequestHandlerComponent();
		$this->Users->RequestHandler->setReturnValue('prefers', true);

		$this->Users->Recaptcha = new UsersControllerMockRecaptchaComponent();
		$this->Users->Recaptcha->setReturnValue('valid', true);

		$this->Users->params['form'] = array();
		$this->Users->register();

		$this->assertTrue(isset($this->Users->viewVars['response']));
		$response = $this->Users->viewVars['response'];
		$this->assertFalse($response['success']);
	}

	function testRegisterBadData()
	{
		$this->Users->data = array(
			'User' => array(
				'name' => null,
				'email' => 'newuser@example.com',
				'username' => 'newuser',
				'password' => $this->Users->Auth->password('newuser'),
				'password2' => 'newuser',
			),
		);	

		$this->Users->params = Router::parse('users/register.json');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->RequestHandler = new UsersControllerMockRequestHandlerComponent();
		$this->Users->RequestHandler->setReturnValue('prefers', true);

		$this->Users->Recaptcha = new UsersControllerMockRecaptchaComponent();
		$this->Users->Recaptcha->setReturnValue('valid', true);

		$this->Users->params['form'] = array();
		$this->Users->register();

		$this->assertTrue(isset($this->Users->viewVars['response']));
		$response = $this->Users->viewVars['response'];
		$this->assertFalse($response['success']);
	}

	function testVerify()
	{
		$user_id = 6;
		$hash = 'HASHHASHHASHHASHHASHHASHHASHHASHHASHHASH';

		$this->Users->params = Router::parse('users/verify/' . $user_id . '/' . $hash);
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->verify($user_id, $hash);

		$conditions = array(
			'User.id' => $user_id,
		);
		$this->Users->User->recursive = -1;
		$result = $this->Users->User->find('first', array('conditions' => $conditions));
		$this->assertFalse(empty($result));

		$this->assertTrue($result['User']['confirmed']);

		$this->assertEqual(substr($this->Users->redirectUrl, 0, 13),  '/users/login/'); 
	}

	function testVerifyNullUserId()
	{
		$user_id = null;
		$hash = 'HASHHASHHASHHASHHASHHASHHASHHASHHASHHASH';

		$this->Users->params = Router::parse('users/verify/' . $user_id . '/' . $hash);
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->verify($user_id, $hash);

		$this->assertEqual($this->Users->error, 'invalid_field');
	}

	function testVerifyInvalidUserId()
	{
		$user_id = 'invalid';
		$hash = 'HASHHASHHASHHASHHASHHASHHASHHASHHASHHASH';

		$this->Users->params = Router::parse('users/verify/' . $user_id . '/' . $hash);
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->verify($user_id, $hash);

		$this->assertEqual($this->Users->error, 'invalid_field');
	}

	function testVerifyInvalidUserIdNotFound()
	{
		$user_id = 9000;
		$hash = 'HASHHASHHASHHASHHASHHASHHASHHASHHASHHASH';

		$this->Users->params = Router::parse('users/verify/' . $user_id . '/' . $hash);
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->verify($user_id, $hash);

		$this->assertEqual($this->Users->error, 'invalid_field');
	}

	function testVerifyNullHash()
	{
		$user_id = 6;
		$hash = null;

		$this->Users->params = Router::parse('users/verify/' . $user_id . '/' . $hash);
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->verify($user_id, $hash);

		$this->assertEqual($this->Users->error, 'invalid_field');
	}

	function testResetlink()
	{
		$user_id = 9090;
		$hash = 'HASHHASHHASHHASHHASHHASHHASHHASHHASHHASH';
		$password = 'newpass';

		$this->Users->data = array(
			'User' => array(
				'password1' => $password,
				'password2' => $password,
			),
		);

		$this->Users->params = Router::parse('users/resetlink/' . $user_id . '/' . $hash);
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->resetlink($user_id, $hash);

		$conditions = array(
			'User.id' => $user_id,
		);
		$this->Users->User->recursive = -1;
		$result = $this->Users->User->find('first', array('conditions' => $conditions));
		$this->assertFalse(empty($result));

		$expected = $this->Users->Auth->password($password);

		$this->assertEqual($result['User']['password'], $expected);
	}

	function testResetlinkNullUserId()
	{
		$user_id = null;
		$hash = 'HASHHASHHASHHASHHASHHASHHASHHASHHASHHASH';

		$this->Users->params = Router::parse('users/resetlink/' . $user_id . '/' . $hash);
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->resetlink($user_id, $hash);

		$this->assertEqual($this->Users->error, 'invalid_field');
	}

	function testResetlinkInvalidUserId()
	{
		$user_id = 'invalid';
		$hash = 'HASHHASHHASHHASHHASHHASHHASHHASHHASHHASH';

		$this->Users->params = Router::parse('users/resetlink/' . $user_id . '/' . $hash);
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->resetlink($user_id, $hash);

		$this->assertEqual($this->Users->error, 'invalid_field');
	}

	function testResetlinkInvalidUserIdNotFound()
	{
		$user_id = 9000;
		$hash = 'HASHHASHHASHHASHHASHHASHHASHHASHHASHHASH';

		$this->Users->params = Router::parse('users/resetlink/' . $user_id . '/' . $hash);
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->resetlink($user_id, $hash);

		$this->assertEqual($this->Users->error, 'invalid_field');
	}

	function testResetlinkInvalidPasswords()
	{
		$user_id = 1;
		$hash = 'HASHHASHHASHHASHHASHHASHHASHHASHHASHHASH';

		$this->Users->data = array(
			'User' => array(
				'password1' => 'not',
				'password2' => 'matching',
			),
		);

		$this->Users->params = Router::parse('users/resetlink/' . $user_id . '/' . $hash);
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->resetlink($user_id, $hash);

		$this->assertEqual($this->Users->error, 'invalid_field');
	}

	function testResetlinkNullHash()
	{
		$user_id = 1;
		$hash = null;

		$this->Users->params = Router::parse('users/resetlink/' . $user_id . '/' . $hash);
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->resetlink($user_id, $hash);

		$this->assertEqual($this->Users->error, 'invalid_field');
	}

	function testResetpass()
	{
		$this->Users->data = array(
			'User' => array(

                        'username'  => 'testuser',
                        'email'  => 'testuser@example.com',

//				'username' => 'selenium1',
//				'email' => 'ethanthomason1+selenium1@gmail.com',
			),
		);

		$this->Users->User->id = $this->Users->Session->read('Auth.User.id');
//		$this->Users->User->id = '9090';

		$this->assertTrue($this->Users->User->saveField('hash', ''));

		$this->Users->params = Router::parse('users/resetpass');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->resetpass();

		$conditions = array(
			'User.id' => $this->Users->Session->read('Auth.User.id'),
//			'User.id' => $this->Users->Session->read('Auth.User.id'),

		);
		$this->Users->User->recursive = -1;
		$result = $this->Users->User->find('first', array('conditions' => $conditions));
		$this->assertFalse(empty($result));
		$this->assertFalse(empty($result['User']['hash']));

//		$this->assertEqual('d39bf13941a29c22640e488740ec8efd71c38a66', $result['User']['hash']);
	}

	function testResetpassNullUser()
	{
		$this->Users->data = array(
			'username' => null,
			'email' => 'testuser@example.com',
		);

		$this->Users->params = Router::parse('users/resetpass');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->resetpass();

		$this->assertEqual($this->Users->error, 'missing_field');
	}

	function testResetpassInvalidUserNotFound()
	{
		$this->Users->data = array(
			'username' => 'notfound',
			'email' => 'notfound@example.com',
		);

		$this->Users->params = Router::parse('users/resetpass');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->resetpass();

		$this->assertEqual($this->Users->error, 'missing_field');
	}

	function testResetpassNullEmail()
	{
		$this->Users->data = array(
			'username' => 'testuser',
			'email' => null,
		);

		$this->Users->params = Router::parse('users/resetpass');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->resetpass();

		$this->assertEqual($this->Users->error, 'missing_field');
	}
/*
	function testChangepass()
	{
		$password = 'longpass';

		$this->Users->data = array(
			'User' => array(
				'current' => 'test',
				'password1' => $password,
				'password2' => $password,
			),
		);

		$this->Users->params = Router::parse('users/changepass');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->changepass();

		$conditions = array(
			'User.id' => $this->Users->Session->read('Auth.User.id'),
		);
		$this->Users->User->recursive = -1;
		$result = $this->Users->User->find('first', array('conditions' => $conditions));

		$expected = $this->Users->Auth->password($password);

		$this->assertEqual($result['User']['password'], $expected);
	}
*/
	function testRenew()
	{
		$this->Users->params = Router::parse('users/renew.json');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->RequestHandler = new UsersControllerMockRequestHandlerComponent();
		$this->Users->RequestHandler->setReturnValue('prefers', true);

		$this->Users->renew();

		$this->assertTrue(isset($this->Users->viewVars['response']));
		$response = $this->Users->viewVars['response'];
		$this->assertTrue($response['success']);
	}

	function testRenewNotJson()
	{
		$this->Users->params = Router::parse('users/renew');
		$this->Users->beforeFilter();
		$this->Users->Component->startup($this->Users);

		$this->Users->renew();

		$this->assertEqual($this->Users->error, 'error404');
	}
	
	function endTest() {
		unset($this->Users);
		ClassRegistry::flush();	
	}
}
?>
