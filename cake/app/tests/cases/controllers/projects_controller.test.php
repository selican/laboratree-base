<?php
App::import('Controller','Projects');
App::import('Component', 'RequestHandler');
App::import('Component', 'Ejabberd');
App::import('Component', 'FileCmp');
App::import('Component', 'Image');
App::import('Component', 'Messaging');

Mock::generatePartial('RequestHandlerComponent', 'ProjectsControllerMockRequestHandlerComponent', array(
	'prefers'
));

Mock::generate('EjabberdComponent', 'ProjectsControllerMockEjabberdComponent');

Mock::generatePartial('FileCmpComponent', 'ProjectsControllerMockFileCmpComponent', array(
	'is_uploaded_file',
	'mimetype',
	'save',
	'remove',
	'exists',
));

Mock::generatePartial('ImageComponent', 'ProjectsControllerMockImageComponent', array(
	'scale',
	'crop',
));

class ProjectsControllerTestMessagingComponent extends MessagingComponent {
	function initialize(&$controller, $settings = array())
	{
		$this->Controller =& $controller;

		parent::initialize($controller, $settings);
	}

	function startup(&$controller) {}

	function email($email, $message, $subject, $template, $attachments = array(), $replyto = '')
	{
		return true;
	}
}

class ProjectsControllerTestProjectsController extends ProjectsController {
	var $name = 'Projects';
	var $autoRender = false;

	var $redirectUrl = null;
	var $renderedAction = null;
	var $error = null;
	var $stopped = null;
	var $components = array(
		'ProjectsControllerTestMessaging',
	);
	
	
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

class ProjectsControllerTest extends CakeTestCase {
	var $Projects = null;
	var $fixtures = array('app.helps', 'app.app_category', 'app.app_data', 'app.application', 'app.app_module', 'app.attachment', 'app.digest', 'app.discussion', 'app.doc', 'app.docs_permission', 'app.docs_tag', 'app.docs_type_data', 'app.docs_type_field', 'app.docs_type', 'app.docs_type_row', 'app.docs_version', 'app.group', 'app.groups_address', 'app.groups_association', 'app.groups_award', 'app.groups_interest', 'app.groups_phone', 'app.groups_projects', 'app.groups_publication', 'app.groups_setting', 'app.groups_url', 'app.groups_users', 'app.inbox', 'app.inbox_hash', 'app.interest', 'app.message_archive', 'app.message', 'app.note', 'app.ontology_concept', 'app.preference', 'app.project', 'app.projects_association', 'app.projects_interest', 'app.projects_setting', 'app.projects_url', 'app.projects_users', 'app.role', 'app.setting', 'app.site_role', 'app.tag', 'app.type', 'app.url', 'app.user', 'app.users_address', 'app.users_association', 'app.users_award', 'app.users_education', 'app.users_interest', 'app.users_job', 'app.users_phone', 'app.users_preference', 'app.users_publication', 'app.users_url', 'app.ldap_user', 'app.word');
	
	function startTest() {
		$this->Projects = new ProjectsControllerTestProjectsController();
		$this->Projects->constructClasses();
		$this->Projects->Component->initialize($this->Projects);
		
		$this->Projects->Session->write('Auth.User', array(
			'id' => 1,
			'name' => 'Test User',
			'username' => 'testuser',
			'changepass' => 0,
			'email' => 'testuser@example.com',
		));
	}

	function testProjectsControllerInstance() {
		$this->assertTrue(is_a($this->Projects, 'ProjectsController'));
	}

	function testIndex()
	{
		$this->Projects->params = Router::parse('projects/index');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->index();

		$this->assertEqual($this->Projects->redirectUrl, '/projects/user');
	}

	function testDashboardDiscussions()
	{
		$project_id = 1;
		$model = 'discussions';

		$this->Projects->params = Router::parse('projects/dashboard/' . $project_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->params['form']['model'] = $model;
		$this->Projects->dashboard($project_id);

		$this->assertTrue(isset($this->Projects->viewVars['list']));
		$list = $this->Projects->viewVars['list'];

		$expected = array(
			'success' => 1,
			'discussions' => array(
				array(
					'id'  => $list['discussions'][0]['id'],
					'table_id'  => 1,
					'table_type' => 'project',
					'title'  => 'Test Topic',
					'created' => $list['discussions'][0]['created'],
					'modified' => $list['discussions'][0]['modified'],
					'lastpost_time' => $list['discussions'][0]['lastpost_time'],
					'lastpost_author' => 'Unknown',
					'lastpost_author_id' => 1,
					'author' => 'Unknown',
					'author_id'  => 1,
					'content'  => 'Test Topic Description',
					'posts' => 0,
					'category' => 'Test Category',
					'parent_id'  => $list['discussions'][0]['parent_id'],
					'text' => 'Test Topic',
					'leaf' => true,
				),
			),
		);
		$this->assertEqual($list, $expected);
	}

	function testDashboardNotes()
	{
		$project_id = 1;
		$model = 'notes';

		$this->Projects->params = Router::parse('projects/dashboard/' . $project_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->params['form']['model'] = $model;
		$this->Projects->dashboard($project_id);

		$this->assertTrue(isset($this->Projects->viewVars['list']));
		$list = $this->Projects->viewVars['list'];

		$expected = array(
			'success' => 1,
			'notes' => array(
				array(
					'id'  => $list['notes'][0]['id'],
					'table_id' => 1,
					'table_type' => 'project',
					'title' => 'Home',
					'created' => $list['notes'][0]['created'],
					'modified' => $list['notes'][0]['modified'],
					'content' => 'Welcome Home',
					'permanent' => 1,
					'group' => 'Project: Private Test Project',
				),
			),
		);
		$this->assertEqual($list, $expected);
	}

	function testDashboardMembers()
	{
		$project_id = 1;
		$model = 'members';

		$this->Projects->params = Router::parse('projects/dashboard/' . $project_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->params['form']['model'] = $model;
		$this->Projects->dashboard($project_id);

		$this->assertTrue(isset($this->Projects->viewVars['list']));
		$list = $this->Projects->viewVars['list'];

		$expected = array(
			'success' => 1,
			'members' => array(
				array(
					'id' => $list['members'][0]['id'],
					'name' => 'Another User',
					'username' => 'anotheruser',
					'session' => 'user:anotheruser',
					'token' => 'user:2',
					'type' => 'user',
					'activity' => $list['members'][0]['activity'],
					'group_id' => 0,
					'project_id' => 1,
					'role_id' => 6,
					'role' => 'project.member',
					'image' => '/img/users/default_small.png',
				),
				array(
					'id' => $list['members'][1]['id'],
					'name' => 'Test User',
					'username' => 'testuser',
					'session' => 'user:testuser',
					'token' => 'user:1',
					'type' => 'user',
					'activity' => $list['members'][1]['activity'],
					'group_id' => 0,
					'project_id' => 1,
					'role_id' => 5,
					'role' => 'project.manager',
					'image' => '/img/users/default_small.png',
				),
			),
		);
		$this->assertEqual($list, $expected);
	}

	function testDashboardDocuments()
	{
		$project_id = 1;
		$model = 'documents';

		$this->Projects->params = Router::parse('projects/dashboard/' . $project_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->params['form']['model'] = $model;
		$this->Projects->dashboard($project_id);

		$this->assertTrue(isset($this->Projects->viewVars['list']));
		$list = $this->Projects->viewVars['list'];

		$expected = array(
			array(
				'id'  => $list[0]['id'],
				'parent_id' => null,
				'title' => 'Test Project - Private',
				'text' => 'Test Project - Private',
				'author' => null,
				'status' => null,
				'size' => null,
				'description' => null,
				'created' => $list[0]['created'],
				'version' => null,
				'version_id' => null,
				'uiProvider' => 'col',
				'cls' => 'folder',
				'iconCls' => 'doc-folder',
				'table_type' => 'project',
				'table_id' => 1,
				'shared' => 0,
				'expandable' => 1,
				'leaf' => null,
				'draggable' => null,
				'tags' => null,
			),
			array(
				'id'  => $list[1]['id'],
				'parent_id' => null,
				'title' => 'Test Project - Public',
				'text' => 'Test Project - Public',
				'author' => null,
				'status' => null,
				'size' => null,
				'description' => null,
				'created' => $list[1]['created'],
				'version' => null,
				'version_id' => null,
				'uiProvider' => 'col',
				'cls' => 'folder',
				'iconCls' => 'doc-folder',
				'table_type' => 'project',
				'table_id' => 1,
				'shared' => 1,
				'expandable' => 1,
				'leaf' => null,
				'draggable' => null,
				'tags' => null,
			),
		);

		$this->assertEqual($list, $expected);
	}

	function testDashboardUrl()
	{
		$project_id = 1;
		$model = 'url';

		$this->Projects->params = Router::parse('projects/dashboard/' . $project_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->params['form']['model'] = $model;
		$this->Projects->dashboard($project_id);

		$this->assertTrue(isset($this->Projects->viewVars['list']));
		$list = $this->Projects->viewVars['list'];

		$expected = array(
			'success' => 1,
			'urls' => array(
				array(
					'id'  => $list['urls'][0]['id'],
					'table_type' => 'project',
					'table_id'  => 1,
					'link'  => 'http://example.com',
					'label'  => 'Test',
					'description' => 'Test',
					'privacy' => 0,
					'group' => 'Project: Private Test Project',
				),
			),
		);
		$this->assertEqual($list, $expected);
	}

	function testDashboardNullProjectId()
	{
		$project_id = null;
		$model = 'projects';

		$this->Projects->params = Router::parse('projects/dashboard/' . $project_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->params['form']['model'] = $model;
		$this->Projects->dashboard($project_id);

		$this->assertEqual($this->Projects->error, 'missing_field');
	}

	function testDashboardInvalidProjectId()
	{
		$project_id = 'invalid';
		$model = 'projects';

		$this->Projects->params = Router::parse('projects/dashboard/' . $project_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->params['form']['model'] = $model;
		$this->Projects->dashboard($project_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testDashboardInvalidProjectIdNotFound()
	{
		$project_id = 9000;
		$model = 'projects';

		$this->Projects->params = Router::parse('projects/dashboard/' . $project_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->params['form']['model'] = $model;
		$this->Projects->dashboard($project_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testDashboardNullModel()
	{
		$project_id = 1;
		$model = null;

		$this->Projects->params = Router::parse('projects/dashboard/' . $project_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->params['form']['model'] = $model;
		$this->Projects->dashboard($project_id);

		$this->assertEqual($this->Projects->error, 'missing_field');
	}

	function testDashboardInvalidModel()
	{
		$project_id = 1;
		$model = 'invalid';

		$this->Projects->params = Router::parse('projects/dashboard/' . $project_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->params['form']['model'] = $model;
		$this->Projects->dashboard($project_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testDashboardAccessDenied()
	{
		$project_id = 2;
		$model = 'projects';

		$this->Projects->params = Router::parse('projects/dashboard/' . $project_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->params['form']['model'] = $model;
		$this->Projects->dashboard($project_id);

		$this->assertEqual($this->Projects->error, 'access_denied');
	}

	function testProfileNullProjectId()
	{
		$project_id = null;

		$this->Projects->params = Router::parse('projects/profile/' . $project_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->profile($project_id);

		$this->assertEqual($this->Projects->error, 'missing_field');
	}

	function testProfileInvalidProjectId()
	{
		$project_id = 'invalid';

		$this->Projects->params = Router::parse('projects/profile/' . $project_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->profile($project_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testProfileInvalidProjectIdNotFound()
	{
		$project_id = 9000;

		$this->Projects->params = Router::parse('projects/profile/' . $project_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->profile($project_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testProfileAccessDenied()
	{
		$project_id = 4;

		$this->Projects->params = Router::parse('projects/profile/' . $project_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->profile($project_id);

		$this->assertEqual($this->Projects->error, 'access_denied');
	}

	function testCreate()
	{
		$this->Projects->data = array(
			'Project' => array(
				'name' => 'Created Project',
				'description' => 'Created Project',
			),
		);

		$this->Projects->params = Router::parse('projects/create');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->create();

		$this->Projects->Ejabberd = new ProjectsControllerMockEjabberdComponent();
		$this->Projects->Ejabberd->setReturnValue('create_room', true);
		$this->Projects->Ejabberd->setReturnValue('set_persistent', true);
		$this->Projects->Ejabberd->setReturnValue('set_logging', true);
		$this->Projects->Ejabberd->setReturnValue('srg_create', true);
		$this->Projects->Ejabberd->setReturnValue('srg_user_add', true);

		$conditions = array(
			'Project.name' => 'Created Project',
		);
		$this->Projects->Project->recursive = -1;
		$result = $this->Projects->Project->find('first', array('conditions' => $conditions));
		$this->assertFalse(empty($result));

		$expected = array(
			'Project' => array(
				'id' => $result['Project']['id'],
				'name' => 'Created Project',
				'email' => $result['Project']['email'],
				'description' => 'Created Project',
				'privacy' => 'private',
				'picture' => null,
				'created' => $result['Project']['created'],
			),
		);
		$this->assertEqual($result, $expected);

		$project_id = $result['Project']['id'];

		$conditions = array(
			'ProjectsUsers.project_id' => $project_id,
			'ProjectsUsers.user_id' => 1,
			'ProjectsUsers.role_id' => $this->Projects->roles['project.manager'],
		);
		$this->Projects->ProjectsUsers->recursive = -1;
		$result = $this->Projects->ProjectsUsers->find('first', array('conditions' => $conditions));
		$this->assertFalse(empty($result));

		$expected = array(
			'ProjectsUsers' => array(
				'id' => $result['ProjectsUsers']['id'],
				'project_id' => $project_id,
				'user_id' => 1,
				'role_id' => $this->Projects->roles['project.manager'],
				'newrole_id' => $result['ProjectsUsers']['newrole_id'],
			),
		);
		$this->assertEqual($result, $expected);

		$conditions = array(
			'Doc.table_type' => 'project',
			'Doc.table_id' => $project_id,
			'Doc.parent_id' => null,
		);
		$this->Projects->Doc->recursive = -1;
		$results = $this->Projects->Doc->find('all', array('conditions' => $conditions));
		$this->assertFalse(empty($results));

		$expected = array(
			array(
				'Doc' => array(
					'id'  => $results[0]['Doc']['id'],
					'table_type' => 'project',
					'table_id'  => $project_id,
					'parent_id'  => null,
					'lft'  => $results[0]['Doc']['lft'],
					'rght'  => $results[0]['Doc']['rght'],
					'filename'  => null,
					'title'  => 'Created Project - Private',
					'name'  => null,
					'path'  => '/Created Project - Private',
					'author_id'  => 1,
					'description'  => null,
					'created'  => $results[0]['Doc']['created'],
					'modified'  => $results[0]['Doc']['modified'],
					'status' => 'in',
					'current_user_id'  => null,
					'shared'  => 0,
					'type' => 'folder'
				),
			),
			array(
				'Doc' => array(
					'id'  => $results[1]['Doc']['id'],
					'table_type' => 'project',
					'table_id'  => $project_id,
					'parent_id'  => null,
					'lft'  => $results[1]['Doc']['lft'],
					'rght'  => $results[1]['Doc']['rght'],
					'filename'  => null,
					'title'  => 'Created Project - Public',
					'name'  => null,
					'path'  => '/Created Project - Public',
					'author_id'  => 1,
					'description'  => null,
					'created'  => $results[1]['Doc']['created'],
					'modified'  => $results[1]['Doc']['modified'],
					'status' => 'in',
					'current_user_id'  => null,
					'shared'  => 1,
					'type' => 'folder'
				),
			),
		);
		$this->assertEqual($results, $expected);

		$conditions = array(
			'Discussion.table_type' => 'project',
			'Discussion.table_id' => $project_id,
			'Discussion.parent_id' => null,
		);
		$this->Projects->Discussion->recursive = -1;
		$result = $this->Projects->Discussion->find('first', array('conditions' => $conditions));
		$this->assertFalse(empty($result));

		$expected = array(
			'Discussion' => array(
				'id'  => $result['Discussion']['id'],
				'table_id'  => $project_id,
				'table_type' => 'project',
				'type' => 'root',
				'author_id'  => 1,
				'title'  => 'Root',
				'created'  => $result['Discussion']['created'],
				'modified'  => $result['Discussion']['modified'],
				'content'  => null,
				'parent_id'  => null,
				'lft'  => $result['Discussion']['lft'],
				'rght'  => $result['Discussion']['rght'],
			),
		);
		$this->assertEqual($result, $expected);

		$conditions = array(
			'Type.table_type' => 'project',
			'Type.table_id' => $project_id,
			'Type.parent_id' => null,
		);
		$this->Projects->Type->recursive = -1;
		$result = $this->Projects->Type->find('first', array('conditions' => $conditions));
		$this->assertFalse(empty($result));

		$expected = array(
			'Type' => array(
				'id'  => $result['Type']['id'],
				'table_type' => 'project',
				'table_id'  => $project_id,
				'name'  => 'Created Project',
				'shared'  => 0,
				'type' => 'root',
				'parent_id'  => null,
				'lft'  => $result['Type']['lft'],
				'rght'  => $result['Type']['rght'],
			),
		);
		$this->assertEqual($result, $expected);

		$conditions = array(
			'Note.table_type' => 'project',
			'Note.table_id' => $project_id,
			'Note.title' => 'Home',
		);
		$this->Projects->Note->recursive = -1;
		$result = $this->Projects->Note->find('first', array('conditions' => $conditions));
		$this->assertFalse(empty($result));

		$expected = array(
			'Note' => array(
				'id'  => $result['Note']['id'],
				'table_type' => 'project',
				'table_id'  => $project_id,
				'title'  => 'Home',
				'content'  => $result['Note']['content'],
				'created'  => $result['Note']['created'],
				'modified'  => $result['Note']['modified'],
				'permanent'  => 1,
				'read_only'  => 0
			),
		);
		$this->assertEqual($result, $expected);

		$this->assertEqual($this->Projects->redirectUrl, '/projects/edit/' . $project_id);
	}

	function testCreateInvalidData()
	{
		$this->Projects->data = array(
			'Project' => array(
				'name' => null,
				'description' => 'Created Project',
			),
		);

		$this->Projects->params = Router::parse('projects/create');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->Ejabberd = new ProjectsControllerMockEjabberdComponent();
		$this->Projects->Ejabberd->setReturnValue('create_room', true);
		$this->Projects->Ejabberd->setReturnValue('set_persistent', true);
		$this->Projects->Ejabberd->setReturnValue('set_logging', true);
		$this->Projects->Ejabberd->setReturnValue('srg_create', true);
		$this->Projects->Ejabberd->setReturnValue('srg_user_add', true);

		$this->Projects->create();

		$this->assertEqual($this->Projects->error, 'internal_error');
	}

	function testEdit()
	{
		$project_id = 1;

		$this->Projects->data = array(
			'Project' => array(
				'name' => 'Edited Project',
				'description' => 'Edited Description',
				'email' => 'edited@example.com',
				'interests' => 'Edited Project 1',
				'privacy' => 'public',
				'picture' => array(
					'name' => null,
					'type' => null,
					'tmp_name' => null,
					'error' => 4,
					'size' => 0,
				),
			),
			'ProjectsUrl' => array(
				'17' => array(
					'label' => 'Google',
					'link' => 'http://www.google.com',
				),
			),
			'ProjectsAssociation' => array(
				'ext-1' => array(
					'label' => 'Test Association',	
					'association' => 'Test Institution',
					'role' => 'Tester',
				),
			),
		);

		$this->Projects->params = Router::parse('projects/edit/' . $project_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->Ejabberd = new ProjectsControllerMockEjabberdComponent();
		$this->Projects->Ejabberd->setReturnValue('srg_set_name', true);
		$this->Projects->Ejabberd->setReturnValue('srg_set_description', true);

		$this->Projects->FileCmp = new ProjectsControllerMockFileCmpComponent();
		$this->Projects->FileCmp->setReturnValue('is_uploaded_file', true);
		$this->Projects->FileCmp->setReturnValue('mimetype', 'image/png');
		$this->Projects->FileCmp->setReturnValue('save', 100);
		$this->Projects->FileCmp->setReturnValue('remove', true);
		$this->Projects->FileCmp->setReturnValue('exists', false);

		$this->Projects->Image = new ProjectsControllerMockImageComponent();
		$this->Projects->Image->setReturnValue('scale', 'picture data');
		$this->Projects->Image->setReturnValue('crop', 'picture data');

		$this->Projects->edit($project_id);

		$conditions = array(
			'Project.id' => $project_id,
		);
		$this->Projects->Project->recursive = -1;
		$result = $this->Projects->Project->find('first', array('conditions' => $conditions));
		$this->assertFalse(empty($result));

		$expected = array(
			'Project' => array(
				'id' => $project_id,
				'name' => 'Edited Project',
				'email' => 'edited@example.com',
				'description' => 'Edited Description',
				'privacy' => 'public',
				'picture' => $result['Project']['picture'],
				'created' => $result['Project']['created'],
			),
		);
		
		sleep(3);

		$this->assertEqual($result, $expected);

		$conditions = array(
			'ProjectsUrl.project_id' => $project_id,
		);
		$this->Projects->ProjectsUrl->recursive = -1;
		sleep(2);
		$results = $this->Projects->ProjectsUrl->find('all', array('conditions' => $conditions));
		$this->assertFalse(empty($results));

		$expected = array(
			array(
				'ProjectsUrl' => array(
					'id' => $results[0]['ProjectsUrl']['id'],
					'project_id' => $project_id,
					'link' => 'http://www.google.com',
					'label' => 'Google',
					'privacy' => 0,
				),
			),
		);
		$this->assertEqual($results, $expected);

		$conditions = array(
			'ProjectsAssociation.project_id' => $project_id,
		);
		$this->Projects->ProjectsAssociation->recursive = -1;
		sleep(2);
		$results = $this->Projects->ProjectsAssociation->find('all', array('conditions' => $conditions));
		$this->assertFalse(empty($results));

		$expected = array(
			array(
				'ProjectsAssociation' => array(
					'id' => $results[0]['ProjectsAssociation']['id'],
					'project_id' => $project_id,
					'label' => 'Test Association',
					'association' => 'Test Institution',
					'role' => 'Tester',
					'privacy' => 0,
				),
			),
		);
		$this->assertEqual($results, $expected);

		$conditions = array(
			'Interest.keyword' => 'editedproject1',
		);
		$this->Projects->Interest->recursive = -1;
		sleep(2);
		$result = $this->Projects->Interest->find('first', array('conditions' => $conditions));
		sleep(3);		
		$this->assertFalse(empty($result));

		$interest_id = $result['Interest']['id'];

		$expected = array(
			'Interest' => array(
				'id' => $result['Interest']['id'],
				'keyword' => 'editedproject1',
				'name' => 'Edited Project 1',
			),
		);
		sleep(2);
		$this->assertEqual($result, $expected);

		$conditions = array(
			'ProjectsInterest.project_id' => $project_id,
		);
		$this->Projects->ProjectsInterest->recursive = -1;
		$results = $this->Projects->ProjectsInterest->find('all', array('conditions' => $conditions));
		$this->assertFalse(empty($results));

		$expected = array(
			array(
				'ProjectsInterest' => array(
					'id' => $results[0]['ProjectsInterest']['id'],
					'project_id' => $project_id,
					'interest_id' => $interest_id,
					'privacy' => 0,
				),
			),
		);
		$this->assertEqual($results, $expected);

		$conditions = array(
			'Doc.table_type' => 'project',
			'Doc.table_id' => $project_id,
			'Doc.parent_id' => null,
		);
		$this->Projects->Doc->recursive = -1;
		$results = $this->Projects->Doc->find('all', array('conditions' => $conditions));
		$this->assertFalse(empty($results));

		$expected = array(
			array(
				'Doc' => array(
					'id'  => $results[0]['Doc']['id'],
					'table_type' => 'project',
					'table_id'  => $project_id,
					'parent_id'  => null,
					'lft'  => $results[0]['Doc']['lft'],
					'rght'  => $results[0]['Doc']['rght'],
					'filename'  => null,
					'title'  => 'Edited Project - Private',
					'name'  => null,
					'path'  => '/Edited Project - Private',
					'author_id'  => 1,
					'description'  => null,
					'created'  => $results[0]['Doc']['created'],
					'modified'  => $results[0]['Doc']['modified'],
					'status' => 'in',
					'current_user_id'  => null,
					'shared'  => 0,
					'type' => 'folder'
				),
			),
			array(
				'Doc' => array(
					'id'  => $results[1]['Doc']['id'],
					'table_type' => 'project',
					'table_id'  => $project_id,
					'parent_id'  => null,
					'lft'  => $results[1]['Doc']['lft'],
					'rght'  => $results[1]['Doc']['rght'],
					'filename'  => null,
					'title'  => 'Edited Project - Public',
					'name'  => null,
					'path'  => '/Edited Project - Public',
					'author_id'  => 1,
					'description'  => null,
					'created'  => $results[1]['Doc']['created'],
					'modified'  => $results[1]['Doc']['modified'],
					'status' => 'in',
					'current_user_id'  => null,
					'shared'  => 1,
					'type' => 'folder'
				),
			),
		);
		$this->assertEqual($results, $expected);

		$this->assertEqual($this->Projects->redirectUrl, '/projects/edit/' . $project_id);
	}

	function testEditJson()
	{
		$project_id = 1;

		$this->Projects->params = Router::parse('projects/edit/' . $project_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->edit($project_id);

		$this->assertTrue(isset($this->Projects->viewVars['node']));
		$node = $this->Projects->viewVars['node'];

		$expected = array(
			'Project' => array(
				'id' => 1,
				'email' => 'testprj+private@example.com',
				'name' => 'Private Test Project',
				'description' => 'Private Test Project',
				'privacy' => 'private',
				'interests' => 'Test Test',
			),
			'ProjectsUrl' => array(
				array(
					'id' => 1,
					'project_id' => 1,
					'link' => 'http://example.com',
					'label' => 'Test',
					'privacy' => 0,
				),
			),
			'ProjectsAssociation' => array(
				array(
					'id' => 1,
					'project_id' => 1,
					'label' => 'Test',
					'association' => 'Test',
					'role' => 'Test',
					'privacy' => 0,
				),
			),
			'User' => array(
				array(
					'id' => 2,
					'name' => 'Another User',
					'ProjectsUsers' => array(
						'id' => 2,
						'project_id' => 1,
						'user_id' => 2,
						'role_id' => 6,
						'newrole_id' => 9,
					),
				),
				array(
					'id' => 1,
					'name' => 'Test User',
					'ProjectsUsers' => array(
						'id' => 1,
						'project_id' => 1,
						'user_id' => 1,
						'role_id' => 5,
						'newrole_id' => 8,
					),
				),
			),
			'Interest' => array(
				array(
					'id' => 1,
					'keyword' => 'testtest',
					'name' => 'Test Test',
					'ProjectsInterest' => array(
						'id' => 1,
						'project_id' => 1,
						'interest_id' => 1,
						'privacy' => 0,
					),
				),
			),
		);

		$this->assertEqual($node, $expected);
	}

	function testEditNullProjectId()
	{
		$project_id = null;

		$this->Projects->params = Router::parse('projects/edit/' . $project_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->edit($project_id);

		$this->assertEqual($this->Projects->error, 'missing_field');
	}

	function testEditInvalidProjectId()
	{
		$project_id = 'invalid';

		$this->Projects->params = Router::parse('projects/edit/' . $project_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->edit($project_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testEditInvalidProjectIdNotFound()
	{
		$project_id = 9000;

		$this->Projects->params = Router::parse('projects/edit/' . $project_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->edit($project_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testEditInvalidData()
	{
		$project_id = 1;

		$this->Projects->data = array(
			'Project' => array(
				'name' => null,
				'description' => 'Edited Description',
				'email' => 'edited@example.com',
				'interests' => 'Edited Project 1',
				'privacy' => 'public',
				'picture' => null, 
			),
		);

		$this->Projects->params = Router::parse('projects/edit/' . $project_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->edit($project_id);

		$this->assertEqual($this->Projects->error, 'internal_error');
	}

	function testEditAccessDenied()
	{
		$project_id = 2;

		$this->Projects->params = Router::parse('projects/edit/' . $project_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->edit($project_id);

		$this->assertEqual($this->Projects->error, 'access_denied');
	}
/*
	function testDelete()
	{
		$project_id = 1;

		$this->Projects->params = Router::parse('projects/delete/' . $project_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->Ejabberd = new ProjectsControllerMockEjabberdComponent();
		$this->Projects->Ejabberd->setReturnValue('destroy_room', true);
		$this->Projects->Ejabberd->setReturnValue('srg_delete', true);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->delete($project_id);

		$this->assertTrue(isset($this->Projects->viewVars['response']));
		$response = $this->Projects->viewVars['response'];
		$this->assertTrue($response['success']);

		$conditions = array(
			'Project.id' => $project_id,
		);
		$this->Projects->Project->recursive = -1;
		$result = $this->Projects->Project->find('first', array('conditions' => $conditions));
		$this->assertTrue(empty($result));
	}

	function testDeleteNotJson()
	{
		$project_id = 1;

		$this->Projects->params = Router::parse('projects/delete/' . $project_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', false);

		$this->Projects->delete($project_id);

		$this->assertEqual($this->Projects->error, 'error404');
	}

	function testDeleteNullProjectId()
	{
		$project_id = null;

		$this->Projects->params = Router::parse('projects/delete/' . $project_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->delete($project_id);

		$this->assertEqual($this->Projects->error, 'missing_field');
	}

	function testDeleteInvalidProjectId()
	{
		$project_id = 'invalid';

		$this->Projects->params = Router::parse('projects/delete/' . $project_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->delete($project_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testDeleteInvalidProjectIdNotFound()
	{
		$project_id = 9000;

		$this->Projects->params = Router::parse('projects/delete/' . $project_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->delete($project_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testDeleteAccessDenied()
	{
		$project_id = 2;

		$this->Projects->params = Router::parse('projects/delete/' . $project_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->delete($project_id);

		$this->assertEqual($this->Projects->error, 'access_denied');
	}
*/
	function testRemoveUser()
	{
		$project_id = 1;
		$user_id = 2;

		$this->Projects->params = Router::parse('projects/removeuser/' . $project_id . '/' . $user_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->Ejabberd = new ProjectsControllerMockEjabberdComponent();
		$this->Projects->Ejabberd->setReturnValue('srg_user_del', true);

		$this->Projects->removeuser($project_id, $user_id);

		$this->assertTrue(isset($this->Projects->viewVars['response']));
		$response = $this->Projects->viewVars['response'];
		$this->assertTrue($response['success']);

		$conditions = array(
			'ProjectsUsers.project_id' => $project_id,
			'ProjectsUsers.user_id' => $user_id,
		);	
		$this->Projects->ProjectsUsers->recursive = -1;
		$results = $this->Projects->ProjectsUsers->find('all', array('conditions' => $conditions));
		$this->assertTrue(empty($results));
	}

	function testRemoveUserNotJson()
	{
		$project_id = 1;
		$user_id = 2;

		$this->Projects->params = Router::parse('projects/removeuser/' . $project_id . '/' . $user_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', false);

		$this->Projects->removeuser($project_id, $user_id);

		$this->assertEqual($this->Projects->error, 'error404');
	}

	function testRemoveUserNullProjectId()
	{
		$project_id = null;
		$user_id = 2;

		$this->Projects->params = Router::parse('projects/removeuser/' . $project_id . '/' . $user_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->removeuser($project_id, $user_id);

		$this->assertEqual($this->Projects->error, 'missing_field');
	}	

	function testRemoveUserInvalidProjectId()
	{
		$project_id = 'invalid';
		$user_id = 2;

		$this->Projects->params = Router::parse('projects/removeuser/' . $project_id . '/' . $user_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->removeuser($project_id, $user_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testRemoveUserInvalidProjectIdNotFound()
	{
		$project_id = 9000;
		$user_id = 2;

		$this->Projects->params = Router::parse('projects/removeuser/' . $project_id . '/' . $user_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->removeuser($project_id, $user_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testRemoveUserNullUserId()
	{
		$project_id = 1;
		$user_id = null;

		$this->Projects->params = Router::parse('projects/removeuser/' . $project_id . '/' . $user_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->removeuser($project_id, $user_id);

		$this->assertEqual($this->Projects->error, 'missing_field');
	}

	function testRemoveUserInvalidUserId()
	{
		$project_id = 1;
		$user_id = 'invalid';

		$this->Projects->params = Router::parse('projects/removeuser/' . $project_id . '/' . $user_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->removeuser($project_id, $user_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testRemoveUserInvalidUserIdNotFound()
	{
		$project_id = 1;
		$user_id = 9000;

		$this->Projects->params = Router::parse('projects/removeuser/' . $project_id . '/' . $user_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->removeuser($project_id, $user_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testRemoveUserInvalidUserIdSelf()
	{
		$project_id = 1;
		$user_id = 1;

		$this->Projects->params = Router::parse('projects/removeuser/' . $project_id . '/' . $user_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->removeuser($project_id, $user_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testRemoveUserInvalidUserIdNotInProject()
	{
		$project_id = 1;
		$user_id = 4;

		$this->Projects->params = Router::parse('projects/removeuser/' . $project_id . '/' . $user_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->removeuser($project_id, $user_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testRemoveUserAccessDenied()
	{
		$project_id = 2;
		$user_id = 2;

		$this->Projects->params = Router::parse('projects/removeuser/' . $project_id . '/' . $user_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->removeuser($project_id, $user_id);

		$this->assertEqual($this->Projects->error, 'access_denied');
	}
/* Feature Removed
	function testInvite()
	{
		$project_id = 1;
		$invite_email = 'does-not-exist@example.com';
		$invite_users = 'user:3';
		$add_email = 'fifth@example.com, sixth@example.com';

		$this->Projects->params = Router::parse('projects/invite/' . $project_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		/*
		 * We can't mock up the MessagingComponent because
		 * it relies on several other components that
		 * wouldn't be loaded. Instead, we extend the
		 * messaging component with our custom messaging
		 * component, and replace it here.
		 */
/*		$this->Projects->Messaging = $this->Projects->ProjectsControllerTestMessaging;

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->params['form']['invite_email'] = $invite_email;
		$this->Projects->params['form']['invite_users'] = $invite_users;
		$this->Projects->params['form']['add_email'] = $add_email;
		$this->Projects->invite($project_id);

		$this->assertTrue(isset($this->Projects->viewVars['response']));
		$response = $this->Projects->viewVars['response'];
		$this->assertTrue($response['success']);

		$expected = array(
			array(
				'user:1',
				'Test User',
			),
			array(
				'user:3',
				'Third User',
			),
			array(
				'email:does-not-exist@example.com',
				'does-not-exist@example.com',
			),
		);

		$this->assertEqual($response['pending'], $expected);
	}

	function testInviteColleagues()
	{
		$project_id = 1;
		$action = 'colleagues';

		$this->Projects->params = Router::parse('projects/invite/' . $project_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->params['form']['action'] = $action;
		$this->Projects->invite($project_id);

		$this->assertTrue(isset($this->Projects->viewVars['response']));
		$response = $this->Projects->viewVars['response'];
		$this->assertTrue($response['success']);

		$expected = array(
			array(
				'id' => 4,
				'name' => 'Fourth User',
				'username' => 'fourthuser',
				'session' => 'user:fourthuser',
				'token' => 'user:4',
				'type' => 'user',
				'activity' => $response['colleagues'][0]['activity'],
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
				'activity' => $response['colleagues'][0]['activity'],
				'group_id' => 0,
				'project_id' => 0,
				'role_id' => 0,
				'role' => 'Unknown',
				'image' => '/img/users/default_small.png',
			),
		);
		$this->assertEqual($response['colleagues'], $expected);
	}

	function testInvitePending()
	{
		$project_id = 1;
		$action = 'pending';

		$this->Projects->params = Router::parse('projects/invite/' . $project_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->params['form']['action'] = $action;
		$this->Projects->invite($project_id);

		$this->assertTrue(isset($this->Projects->viewVars['response']));
		$response = $this->Projects->viewVars['response'];
		$this->assertTrue($response['success']);

		$expected = array(
			array(
				'user:1',
				'Test User',
			),
		);
		$this->assertEqual($response['pending'], $expected);
	} */
// Removed because we are testing it in Selenium for now
/*
	function testInviteSearch()
	{
		$project_id = 1;
		$action = 'search';
		$query = 'third';

		$this->Projects->params = Router::parse('projects/invite/' . $project_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->params['form']['action'] = $action;
		$this->Projects->params['form']['query'] = $query;
		$this->Projects->invite($project_id);

		$this->assertTrue(isset($this->Projects->viewVars['response']));
		$response = $this->Projects->viewVars['response'];
		$this->assertTrue($response['success']);

		$expected = array(
			array(
				'token' => 'user:3',
				'name' => 'Third User',
			),
		);
		$this->assertEqual($response['results'], $expected);
	}

	function testInviteAddEmail()
	{
		$project_id = 1;
		$add_email = 'newuser@example.com';

		$this->Projects->params = Router::parse('projects/invite/' . $project_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->params['form']['add_email'] = $add_email;
		$this->Projects->invite($project_id);

		$this->assertTrue(isset($this->Projects->viewVars['response']));
		$response = $this->Projects->viewVars['response'];
		$this->assertTrue($response['success']);

		$username = array_shift(explode('@', $add_email));

		$conditions = array(
			'User.username' => $username,
			'User.email' => $add_email,
			'User.name' => $username,
		);
		$result = $this->Projects->User->find('first', array('conditions' => $conditions));

		$expected = array(
			'id' => $result['User']['id'],
			'username' => $username,
			'password' => $result['User']['password'],
			'email' => $add_email,
			'alt_email' => null,
			'prefix' => null,
			'first_name' => null,
			'last_name' => null,
			'name' => 'newuser',
			'suffix' => null,
			'title' => null,
			'description' => null,
			'status' => null,
			'gender' => 'unknown',
			'age' => null,
			'picture' => null,
			'privacy' => 'private',
			'activity' => '1970-01-01 00:00:00',
			'registered' => $result['User']['registered'],
			'hash' => $result['User']['hash'],
			'private_hash' => $result['User']['private_hash'],
			'auth_token' => null,
			'auth_timestamp' => 0,
			'confirmed' => 0,
			'changepass' => 1,
			'security_question' => 0,
			'security_answer' => null,
			'language_id' => 1,
			'timezone_id' => 39,
			'ip' => null,
			'admin' => 0,
			'type' => 'user',
			'vivo' => null,
		);

		$this->assertEqual($result['User'], $expected);
	}

	function testInviteNullProjectId()
	{
		$project_id = null;
		$email = 'fourthuser@example.com';

		$this->Projects->params = Router::parse('projects/invite/' . $project_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->params['form']['email'] = $email;
		$this->Projects->invite($project_id);

		$this->assertEqual($this->Projects->error, 'missing_field');
	}	

	function testInviteInvalidProjectId()
	{
		$project_id = 'invalid';
		$email = 'fourthuser@example.com';

		$this->Projects->params = Router::parse('projects/invite/' . $project_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->params['form']['email'] = $email;
		$this->Projects->invite($project_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testInviteInvalidProjectIdNotFound()
	{
		$project_id = 9000;
		$email = 'fourthuser@example.com';

		$this->Projects->params = Router::parse('projects/invite/' . $project_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->params['form']['email'] = $email;
		$this->Projects->invite($project_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testInviteAccessDenied()
	{
		$project_id = 2;
		$email = 'fourthuser@example.com';

		$this->Projects->params = Router::parse('projects/invite/' . $project_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->params['form']['email'] = $email;
		$this->Projects->invite($project_id);

		$this->assertEqual($this->Projects->error, 'access_denied');
	}

	function testInviteInvalidInviteEmail()
	{
		$project_id = 1;
		$invite_email = 'invalid';

		$this->Projects->params = Router::parse('projects/invite/' . $project_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->params['form']['invite_email'] = $invite_email;
		$this->Projects->invite($project_id);

		$this->assertTrue(isset($this->Projects->viewVars['response']));
		$response = $this->Projects->viewVars['response'];
		$this->assertTrue($response['success']);

		$expected = array(
			array(
				'user:1',
				'Test User',
			),
		);
		$this->assertEqual($response['pending'], $expected);
	}

	function testInviteInvalidInviteUsers()
	{
		$project_id = 1;
		$invite_users = 'invalid';

		$this->Projects->params = Router::parse('projects/invite/' . $project_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->params['form']['invite_users'] = $invite_users;
		$this->Projects->invite($project_id);

		$this->assertTrue(isset($this->Projects->viewVars['response']));
		$response = $this->Projects->viewVars['response'];
		$this->assertTrue($response['success']);

		$expected = array(
			array(
				'user:1',
				'Test User',
			),
		);
		$this->assertEqual($response['pending'], $expected);
	}

	function testInviteInvalidAction()
	{
		$project_id = 1;
		$action = 'invalid';

		$this->Projects->params = Router::parse('projects/invite/' . $project_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->params['form']['action'] = $action;
		$this->Projects->invite($project_id);

		$this->assertTrue(isset($this->Projects->viewVars['response']));
		$response = $this->Projects->viewVars['response'];
		$this->assertFalse($response['success']);
	}

	function testInviteInvalidQuery()
	{
		$project_id = 1;
		$action = 'search';
		$query = '#NOTFOUND#';

		$this->Projects->params = Router::parse('projects/invite/' . $project_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->params['form']['action'] = $action;
		$this->Projects->params['form']['query'] = $query;
		$this->Projects->invite($project_id);

		$this->assertTrue(isset($this->Projects->viewVars['response']));
		$response = $this->Projects->viewVars['response'];
		$this->assertTrue($response['success']);

		$this->assertTrue(empty($response['result']));
	}
*/
	function testAccept()
	{
		$project_id = 2;
		$inbox_id = 11;

		$this->Projects->params = Router::parse('projects/accept/' . $project_id . '/' . $inbox_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->Ejabberd = new ProjectsControllerMockEjabberdComponent();
		$this->Projects->Ejabberd->setReturnValue('srg_user_add', true);

		$this->Projects->accept($project_id, $inbox_id);

		$this->assertEqual($this->Projects->redirectUrl, '/projects/dashboard/' . $project_id);
	}

	function testAcceptNullProjectId()
	{
		$project_id = null;
		$inbox_id = 11;

		$this->Projects->params = Router::parse('projects/accept/' . $project_id . '/' . $inbox_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->accept($project_id, $inbox_id);

		$this->assertEqual($this->Projects->error, 'missing_field');
	}

	function testAcceptInvalidProjectId()
	{
		$project_id = 'invalid';
		$inbox_id = 11;

		$this->Projects->params = Router::parse('projects/accept/' . $project_id . '/' . $inbox_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->accept($project_id, $inbox_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testAcceptNullInboxId()
	{
		$project_id = 2;
		$inbox_id = null;

		$this->Projects->params = Router::parse('projects/accept/' . $project_id . '/' . $inbox_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->accept($project_id, $inbox_id);

		$this->assertEqual($this->Projects->error, 'missing_field');
	}

	function testAcceptInvalidInboxId()
	{
		$project_id = 2;
		$inbox_id = 'invalid';

		$this->Projects->params = Router::parse('projects/accept/' . $project_id . '/' . $inbox_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->accept($project_id, $inbox_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testAcceptInvalidProjectNotFound()
	{
		$project_id = 9000;
		$inbox_id = 11;

		$this->Projects->params = Router::parse('projects/accept/' . $project_id . '/' . $inbox_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->accept($project_id, $inbox_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testAcceptInboxIdNotFound()
	{
		$project_id = 2;
		$inbox_id = 9000;

		$this->Projects->params = Router::parse('projects/accept/' . $project_id . '/' . $inbox_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->accept($project_id, $inbox_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	// Person viewing invite message is not the recipient.
	function testAcceptAccessDenied()
	{
		$project_id = 1;
		$inbox_id = 11;

		$this->Projects->Session->write('Auth.User', array(
			'id' => 3,
			'username' => 'thirduser',
			'changepass' => 0,
			'email' => 'thirduser@example.com',
		));

		$this->Projects->params = Router::parse('projects/accept/' . $project_id . '/' . $inbox_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->accept($project_id, $inbox_id);

		$this->assertEqual($this->Projects->error, 'access_denied');
	}

	// Invite where invitee is already a member of the project.
	function testAcceptInvalidInviteRelationshipAlreadyExists()
	{
		$project_id = 1;
		$inbox_id = 12;

		$this->Projects->params = Router::parse('projects/accept/' . $project_id . '/' . $inbox_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->accept($project_id, $inbox_id);

		$this->assertEqual($this->Projects->error, 'internal_error');
	}

	// Join request where person viewing the request is not a manager of the project.
	function testAcceptAccessDeniedProjectRequest()
	{
		$project_id = 1;
		$inbox_id = 13;

		$this->Projects->Session->write('Auth.User', array(
			'id' => 3,
			'username' => 'thirduser',
			'changepass' => 0,
			'email' => 'thirduser@example.com',
		));

		$this->Projects->params = Router::parse('projects/accept/' . $project_id . '/' . $inbox_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->accept($project_id, $inbox_id);

		$this->assertEqual($this->Projects->error, 'access_denied');
	}

	// Join request where the person who made the request is already a member of the project.
	function testAcceptInvalidRequestRelationshipAlreadyExists()
	{
		$project_id = 1;
		$inbox_id = 14;

		$this->Projects->params = Router::parse('projects/accept/' . $project_id . '/' . $inbox_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->accept($project_id, $inbox_id);

		$this->assertEqual($this->Projects->error, 'internal_error');
	}

	// Message is not a request or invitation.
	function testAcceptInvalidTemplate()
	{
		$project_id = 1;
		$inbox_id = 7;

		$this->Projects->params = Router::parse('projects/accept/' . $project_id . '/' . $inbox_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->accept($project_id, $inbox_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testLeave()
	{
		$project_id = 1;

		$this->Projects->Session->write('Auth.User', array(
			'id' => 2,
			'username' => 'anotheruser',
			'changepass' => 0,
			'email' => 'anotheruser@example.com',
		));

		$this->Projects->params = Router::parse('projects/leave/' . $project_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->Ejabberd = new ProjectsControllerMockEjabberdComponent();
		$this->Projects->Ejabberd->setReturnValue('srg_user_del', true);

		$this->Projects->leave($project_id);

		$this->assertEqual($this->Projects->redirectUrl, '/projects/user/' . $this->Projects->Session->read('Auth.User.id'));
	}

	function testLeaveNullProjectId()
	{
		$project_id = null;

		$this->Projects->params = Router::parse('projects/leave/' . $project_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->leave($project_id);

		$this->assertEqual($this->Projects->error, 'missing_field');
	}

	function testLeaveInvalidProjectId()
	{
		$project_id = 'invalid';

		$this->Projects->params = Router::parse('projects/leave/' . $project_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->leave($project_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testLeaveInvalidProjectNotFound()
	{
		$project_id = 9000;

		$this->Projects->params = Router::parse('projects/leave/' . $project_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->leave($project_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	// The last project manager attempts to leave the project. 
	function testLeaveLastManager()
	{
		$project_id = 1;

		$this->Projects->params = Router::parse('projects/leave/' . $project_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->leave($project_id);

		$this->assertEqual($this->Projects->error, 'internal_error');
	}

	function testLeaveInvalidPersonNotInProject()
	{
		$project_id = 1;

		$this->Projects->Session->write('Auth.User', array(
			'id' => 3,
			'username' => 'thirduser',
			'changepass' => 0,
			'email' => 'thirduser@example.com',
		));

		$this->Projects->params = Router::parse('projects/leave/' . $project_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->leave($project_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}
// Removed because of Selenium testing
/*
	function testPromote()
	{
		$project_id = 3;
		$user_id = 3;

		$this->Projects->params = Router::parse('projects/promote/' . $project_id . '/' . $user_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->promote($project_id, $user_id);

		$this->assertTrue(isset($this->Projects->viewVars['response']));
		$response = $this->Projects->viewVars['response'];
		$this->assertTrue($response['success']);

		$conditions = array(
			'ProjectsUsers.project_id' => $project_id,
			'ProjectsUsers.user_id' => $user_id,
		);
		$this->Projects->ProjectsUsers->recursive = -1;
		$result = $this->Projects->ProjectsUsers->find('first', array('conditions' => $conditions));
		$this->assertFalse(empty($result));

		$this->assertEqual($result['ProjectsUsers']['role_id'], $this->Projects->roles['project.manager']);
	}

	function testPromoteNotJson()
	{
		$project_id = 1;
		$user_id = 2;

		$this->Projects->params = Router::parse('projects/promote/' . $project_id . '/' . $user_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', false);

		$this->Projects->promote($project_id, $user_id);

		$this->assertEqual($this->Projects->error, 'error404');
	}

	function testPromoteNullProjectId()
	{
		$project_id = null;
		$user_id = 2;

		$this->Projects->params = Router::parse('projects/promote/' . $project_id . '/' . $user_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->promote($project_id, $user_id);

		$this->assertEqual($this->Projects->error, 'missing_field');
	}

	function testPromoteInvalidProjectId()
	{
		$project_id = 'invalid';
		$user_id = 2;

		$this->Projects->params = Router::parse('projects/promote/' . $project_id . '/' . $user_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->promote($project_id, $user_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testPromoteInvalidProjectIdNotFound()
	{
		$project_id = 9000;
		$user_id = 2;

		$this->Projects->params = Router::parse('projects/promote/' . $project_id . '/' . $user_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->promote($project_id, $user_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testPromoteNullUserId()
	{
		$project_id = 1;
		$user_id = null;

		$this->Projects->params = Router::parse('projects/promote/' . $project_id . '/' . $user_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->promote($project_id, $user_id);

		$this->assertEqual($this->Projects->error, 'missing_field');
	}

	function testPromoteInvalidUserId()
	{
		$project_id = 1;
		$user_id = 'invalid';

		$this->Projects->params = Router::parse('projects/promote/' . $project_id . '/' . $user_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->promote($project_id, $user_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testPromoteInvalidUserIdNotFound()
	{
		$project_id = 1;
		$user_id = 9000;

		$this->Projects->params = Router::parse('projects/promote/' . $project_id . '/' . $user_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->promote($project_id, $user_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testPromoteAccessDenied()
	{
		$project_id = 2;
		$user_id = 2;

		$this->Projects->params = Router::parse('projects/promote/' . $project_id . '/' . $user_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->promote($project_id, $user_id);

		$this->assertEqual($this->Projects->error, 'access_denied');
	}

	function testPromoteInvalidRelationship()
	{
		$project_id = 1;
		$user_id = 3;

		$this->Projects->params = Router::parse('projects/promote/' . $project_id . '/' . $user_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->promote($project_id, $user_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testPromoteInvalidRoleNotFound()
	{
		$project_id = 3;
		$user_id = 3;

		$this->Projects->Session->write('Auth.User', array(
			'id' => 2,
			'username' => 'anotheruser',
			'changepass' => 0,
			'email' => 'anotheruser@example.com',
		));

		$this->Projects->params = Router::parse('projects/promote/' . $project_id . '/' . $user_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->promote($project_id, $user_id);

		$this->assertEqual($this->Projects->error, 'internal_error');
	}

	function testDemote()
	{
		$project_id = 3;
		$user_id = 4;

		$this->Projects->params = Router::parse('projects/demote/' . $project_id . '/' . $user_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->demote($project_id, $user_id);

		$this->assertTrue(isset($this->Projects->viewVars['response']));
		$response = $this->Projects->viewVars['response'];
		$this->assertTrue($response['success']);

		$conditions = array(
			'ProjectsUsers.project_id' => $project_id,
			'ProjectsUsers.user_id' => $user_id,
		);
		$this->Projects->ProjectsUsers->recursive = -1;
		$result = $this->Projects->ProjectsUsers->find('first', array('conditions' => $conditions));
		$this->assertFalse(empty($result));

		$this->assertEqual($result['ProjectsUsers']['role_id'], $this->Projects->roles['project.member']);
	}

	function testDemoteNotJson()
	{
		$project_id = 3;
		$user_id = 2;

		$this->Projects->params = Router::parse('projects/demote/' . $project_id . '/' . $user_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', false);

		$this->Projects->demote($project_id, $user_id);

		$this->assertEqual($this->Projects->error, 'error404');
	}

	function testDemoteNullProjectId()
	{
		$project_id = null;
		$user_id = 2;

		$this->Projects->params = Router::parse('projects/demote/' . $project_id . '/' . $user_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->demote($project_id, $user_id);

		$this->assertEqual($this->Projects->error, 'missing_field');
	}

	function testDemoteInvalidProjectId()
	{
		$project_id = 'invalid';
		$user_id = 2;

		$this->Projects->params = Router::parse('projects/demote/' . $project_id . '/' . $user_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->demote($project_id, $user_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testDemoteInvalidProjectIdNotFound()
	{
		$project_id = 9000;
		$user_id = 2;

		$this->Projects->params = Router::parse('projects/demote/' . $project_id . '/' . $user_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->demote($project_id, $user_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testDemoteNullUserId()
	{
		$project_id = 3;
		$user_id = null;

		$this->Projects->params = Router::parse('projects/demote/' . $project_id . '/' . $user_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->demote($project_id, $user_id);

		$this->assertEqual($this->Projects->error, 'missing_field');
	}

	function testDemoteInvalidUserId()
	{
		$project_id = 3;
		$user_id = 'invalid';

		$this->Projects->params = Router::parse('projects/demote/' . $project_id . '/' . $user_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->demote($project_id, $user_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testDemoteInvalidUserIdNotFound()
	{
		$project_id = 3;
		$user_id = 9000;

		$this->Projects->params = Router::parse('projects/demote/' . $project_id . '/' . $user_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->demote($project_id, $user_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testDemoteInvalidUserIdSelf()
	{
		$project_id = 1;
		$user_id = 1;

		$this->Projects->params = Router::parse('projects/demote/' . $project_id . '/' . $user_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->demote($project_id, $user_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testDemoteAccessDenied()
	{
		$project_id = 2;
		$user_id = 2;

		$this->Projects->params = Router::parse('projects/demote/' . $project_id . '/' . $user_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->demote($project_id, $user_id);

		$this->assertEqual($this->Projects->error, 'access_denied');
	}

	function testDemoteInvalidRelationship()
	{
		$project_id = 1;
		$user_id = 3;

		$this->Projects->params = Router::parse('projects/demote/' . $project_id . '/' . $user_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->demote($project_id, $user_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testDemoteInvalidRoleNotFound()
	{
		$project_id = 3;
		$user_id = 3;

		$this->Projects->Session->write('Auth.User', array(
			'id' => 2,
			'username' => 'anotheruser',
			'changepass' => 0,
			'email' => 'anotheruser@example.com',
		));

		$this->Projects->params = Router::parse('projects/demote/' . $project_id . '/' . $user_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->demote($project_id, $user_id);

		$this->assertEqual($this->Projects->error, 'internal_error');
	}
*/
	function testUser()
	{
		$this->Projects->params = Router::parse('projects/user.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->user();

		$this->assertTrue(isset($this->Projects->viewVars['nodes']));
		$nodes = $this->Projects->viewVars['nodes'];

		$expected = array(
			'success' => 1,
			'projects' => array(
				array(
					'id'  => 1,
					'name' => 'Private Test Project',
					'text' => null,
					'leaf' => null,
					'description' => null,
					'session' => 'group:project_1',
					'token' => 'project:1',
					'type' => 'project',
					'email' => 'testprj+private@example.com',
					'privacy' => 'private',
					'image' => '/img/projects/default_small.png',
					'role' => 'project.manager',
					'members' => 2,
					'group' => '',
					'group_type' => '',
					'group_id' => '',
				),
				array(
					'id'  => 3,
					'name' => 'Another Private Test Project',
					'text' => null,
                                        'leaf' => null,
                                        'description' => null,
					'session' => 'group:project_3',
					'token' => 'project:3',
					'type' => 'project',
					'email' => 'anotherprj+private@example.com',
					'privacy' => 'private',
					'image' => '/img/projects/default_small.png',
					'role' => 'project.manager',
					'members' => 4,
					'group' => '',
					'group_type' => '',
					'group_id' => '',
				),
			),
		);
		$this->assertEqual($nodes, $expected);
	}

	function testUserInvalidLimit()
	{
		$limit = 'invalid';

		$this->Projects->params = Router::parse('projects/user.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->params['form']['limit'] = $limit;
		$this->Projects->user();

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}	

	function testUserInvalidStart()
	{
		$start = 'invalid';

		$this->Projects->params = Router::parse('projects/user.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->params['form']['start'] = $start;
		$this->Projects->user();

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testMembers()
	{
		$project_id = 1;

		$this->Projects->params = Router::parse('projects/members/' . $project_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->members($project_id);

		$this->assertTrue(isset($this->Projects->viewVars['response']));
		$response = $this->Projects->viewVars['response'];
		$this->assertTrue($response['success']);

		$expected = array(
			'success' => 1,	
			'members' => array(
				array(
					'id' => 2,
					'name' => 'Another User',
					'username' => 'anotheruser',
					'session' => 'user:anotheruser',
					'token' => 'user:2',
					'type' => 'user',
					'activity' => $response['members'][0]['activity'],
					'group_id' => 0,
					'project_id' => $project_id,
					'role_id' => 6,
					'role' => 'project.member',
					'image' => '/img/users/default_small.png',
				),
				array(
					'id' => 1,
					'name' => 'Test User',
					'username' => 'testuser',
					'session' => 'user:testuser',
					'token' => 'user:1',
					'type' => 'user',
					'activity' => $response['members'][1]['activity'],
					'group_id' => 0,
					'project_id' => $project_id,
					'role_id' => 5,
					'role' => 'project.manager',
					'image' => '/img/users/default_small.png',
				),
			),
		);
		$this->assertEqual($response, $expected);
	}

	function testMembersNullProjectId()
	{
		$project_id = null;

		$this->Projects->params = Router::parse('projects/members/' . $project_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->members($project_id);

		$this->assertEqual($this->Projects->error, 'missing_field');
	}

	function testMembersInvalidProjectId()
	{
		$project_id = 'invalid';

		$this->Projects->params = Router::parse('projects/members/' . $project_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->members($project_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}	

	function testMembersInvalidProjectIdNotFound()
	{
		$project_id = 9000;

		$this->Projects->params = Router::parse('projects/members/' . $project_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->members($project_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testMembersAccessDenied()
	{
		$project_id = 2;

		$this->Projects->params = Router::parse('projects/members/' . $project_id . '.json');
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->RequestHandler = new ProjectsControllerMockRequestHandlerComponent();
		$this->Projects->RequestHandler->setReturnValue('prefers', true);

		$this->Projects->members($project_id);

		$this->assertEqual($this->Projects->error, 'access_denied');
	}

	function testRequest()
	{
		$project_id = 2;

		$this->Projects->Session->write('Auth.User', array(
			'id' => 3,
			'username' => 'thirduser',
			'name' => 'Third User',
			'changepass' => 0,
			'email' => 'thirduser@example.com',
		));

		$this->Projects->params = Router::parse('projects/request/' . $project_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		/*
		 * We can't mock up the MessagingComponent because
		 * it relies on several other components that
		 * wouldn't be loaded. Instead, we extend the
		 * messaging component with our custom messaging
		 * component, and replace it here.
		 */
		//$this->Projects->Messaging = $this->Projects->ProjectsControllerTestMessaging;

		$this->Projects->request($project_id);

		$conditions = array(
			'Inbox.sender_id' => $this->Projects->Session->read('Auth.User.id'),
			'Inbox.template' => 'project_request',
			'Inbox.template_data LIKE' => '%"project_id":"' . $project_id .'"%',
			'Inbox.type' => 'received',
		);
		$this->Projects->Inbox->recursive = -1;
		$result = $this->Projects->Inbox->find('first', array('conditions' => $conditions));
		$this->assertFalse(empty($result));

		/*
		 * TODO: Figure this out.
		 *
		 * There is something wonky here with the
		 * way that Simpletest or CakePHP is
		 * handling template_data and it's
		 * quotes.
		 */

		$expected = array(
			'Inbox' => array(
				'id'  => $result['Inbox']['id'],
				'sender_id'  => $this->Projects->Session->read('Auth.User.id'),
				'receiver_id'  => $result['Inbox']['receiver_id'],
				'receiver_type' => 'user',
				'message_id'  => $result['Inbox']['message_id'],
				'template'  => 'project_request',
				'template_data'  => '{"sender":"Third User","sender_id":"3","project":"Public Test Project","project_id":"2"}',
				'status' => 'unread',
				'trash'  => 0,
				'type' => 'received',
				'email'  => NULL,
				'parent_id'  => NULL
			),
		);
		$this->assertEqual($result, $expected);

		$this->assertEqual($this->Projects->redirectUrl, '/projects/profile/' . $project_id);
	}

	function testRequestNullProjectId()
	{
		$project_id = null;

		$this->Projects->params = Router::parse('projects/request/' . $project_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->request($project_id);

		$this->assertEqual($this->Projects->error, 'missing_field');
	}

	function testRequestInvalidProjectId()
	{
		$project_id = 'invalid';

		$this->Projects->params = Router::parse('projects/request/' . $project_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->request($project_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testRequestInvalidProjectIdNotFound()
	{
		$project_id = 9000;

		$this->Projects->params = Router::parse('projects/request/' . $project_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->request($project_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testRequestInvalidRole()
	{
		$project_id = 1;

		$this->Projects->params = Router::parse('projects/request/' . $project_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->request($project_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}

	function testRequestInvalidRequest()
	{
		$project_id = 1;

		$this->Projects->Session->write('Auth.User', array(
			'id' => 3,
			'username' => 'thirduser',
			'changepass' => 0,
			'email' => 'thirduser@example.com',
		));

		$this->Projects->params = Router::parse('projects/request/' . $project_id);
		$this->Projects->beforeFilter();
		$this->Projects->Component->startup($this->Projects);

		$this->Projects->request($project_id);

		$this->assertEqual($this->Projects->error, 'invalid_field');
	}
	
	function endTest() {
		unset($this->Projects);
		ClassRegistry::flush();	
	}
}
?>
