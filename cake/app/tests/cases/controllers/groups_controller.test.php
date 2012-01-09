<?php
App::import('Controller','Groups');
App::import('Component', 'RequestHandler');
App::import('Component', 'Ejabberd');
App::import('Component', 'FileCmp');
App::import('Component', 'Image');
App::import('Component', 'Messaging');

Mock::generatePartial('RequestHandlerComponent', 'GroupsControllerMockRequestHandlerComponent', array(
	'prefers'
));

Mock::generatePartial('EjabberdComponent', 'GroupsControllerMockEjabberdComponent', array(
	'create_room',
	'set_persistent',
	'set_logging',
	'srg_create',
	'srg_user_add',
	'srg_user_del',
	'srg_set_name',
	'srg_set_description',
	'destroy_room',
	'srg_delete',
));

Mock::generatePartial('FileCmpComponent', 'GroupsControllerMockFileCmpComponent', array(
	'is_uploaded_file',
	'mimetype',
	'save',
	'remove',
	'exists',
));

Mock::generatePartial('ImageComponent', 'GroupsControllerMockImageComponent', array(
	'scale',
	'crop',
));

class GroupsControllerTestMessagingComponent extends MessagingComponent {
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

class GroupsControllerTestGroupsController extends GroupsController {
	var $name = 'Groups';
	var $autoRender = false;

	var $redirectUrl = null;
	var $components = array(
		'GroupsControllerTestMessaging',
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

class GroupsControllerTest extends CakeTestCase {
	var $Groups = null;
	var $fixtures = array('app.helps', 'app.app_category', 'app.app_data', 'app.application', 'app.app_module', 'app.attachment', 'app.digest', 'app.discussion', 'app.doc', 'app.docs_permission', 'app.docs_tag', 'app.docs_type_data', 'app.docs_type_field', 'app.docs_type', 'app.docs_type_row', 'app.docs_version', 'app.group', 'app.groups_address', 'app.groups_association', 'app.groups_award', 'app.groups_interest', 'app.groups_phone', 'app.groups_projects', 'app.groups_publication', 'app.groups_setting', 'app.groups_url', 'app.groups_users', 'app.inbox', 'app.inbox_hash', 'app.interest', 'app.message_archive', 'app.message', 'app.note', 'app.ontology_concept', 'app.preference', 'app.project', 'app.projects_association', 'app.projects_interest', 'app.projects_setting', 'app.projects_url', 'app.projects_users', 'app.role', 'app.setting', 'app.site_role', 'app.tag', 'app.type', 'app.url', 'app.user', 'app.users_address', 'app.users_association', 'app.users_award', 'app.users_education', 'app.users_interest', 'app.users_job', 'app.users_phone', 'app.users_preference', 'app.users_publication', 'app.users_url', 'app.ldap_user', 'app.word');
	
	function startTest() {
		$this->Groups = new GroupsControllerTestGroupsController();
		$this->Groups->constructClasses();
		$this->Groups->Component->initialize($this->Groups);

		$this->Groups->Session->write('Auth.User', array(
			'id' => 1,
			'username' => 'testuser',
			'changepass' => 0,
			'email' => 'testuser@example.com',
		));
	}
	
	function testGroupsControllerInstance() {
		$this->assertTrue(is_a($this->Groups, 'GroupsController'));
	}

	function testIndex()
	{
		$this->Groups->params = Router::parse('groups/index');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->index();

		$this->assertEqual($this->Groups->redirectUrl, '/groups/user');
	}

	function testDashboardProjects()
	{
		$group_id = 1;
		$model = 'projects';

		$this->Groups->params = Router::parse('groups/dashboard/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->params['form']['model'] = $model;
		$this->Groups->dashboard($group_id);

		$this->assertTrue(isset($this->Groups->viewVars['list']));
		$list = $this->Groups->viewVars['list'];

		$expected = array(
			'success' => 1,
			'projects' => array(
				array(
					'id'  => 1,
					'name' => 'Private Test Project',
					'text' => 'Private Test Project',
					'leaf' => true,
					'description' => 'Private Test Project',
					'session' => 'group:project_1',
					'token' => 'project:1',
					'type' => 'project',
					'email' => 'testprj+private@example.com',
					'privacy' => 'private',
					'image' => '/img/projects/default_small.png',
					'role' => 'project.manager',
					'members' => $list['projects'][0]['members'],
					'group' => 'Group: Private Test Group',
					'group_type' => 'group',
					'group_id' => 1,
				),
			),
		);
		$this->assertEqual($list, $expected);
	}

	function testDashboardDiscussions()
	{
		$group_id = 1;
		$model = 'discussions';

		$this->Groups->params = Router::parse('groups/dashboard/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->params['form']['model'] = $model;
		$this->Groups->dashboard($group_id);

		$this->assertTrue(isset($this->Groups->viewVars['list']));
		$list = $this->Groups->viewVars['list'];

		$expected = array(
			'success' => 1,
			'discussions' => array(
				array(
					'id'  => 3,
					'table_id'  => 1,
					'table_type' => 'group',
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
					'parent_id'  => 2,
					'text' => 'Test Topic',
					'leaf' => true,
				),
			),
		);
		$this->assertEqual($list, $expected);
	}

	function testDashboardNotes()
	{
		$group_id = 1;
		$model = 'notes';

		$this->Groups->params = Router::parse('groups/dashboard/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->params['form']['model'] = $model;
		$this->Groups->dashboard($group_id);

		$this->assertTrue(isset($this->Groups->viewVars['list']));
		$list = $this->Groups->viewVars['list'];

		$expected = array(
			'success' => 1,
			'notes' => array(
				array(
					'id'  => 2,
					'table_id' => 1,
					'table_type' => 'group',
					'title' => 'Home',
					'created' => $list['notes'][0]['created'],
					'modified' => $list['notes'][0]['modified'],
					'content' => 'Welcome Home',
					'permanent' => 1,
					'group' => 'Group: Private Test Group',
				),
			),
		);
		$this->assertEqual($list, $expected);
	}

	function testDashboardMembers()
	{
		$group_id = 1;
		$model = 'members';

		$this->Groups->params = Router::parse('groups/dashboard/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->params['form']['model'] = $model;
		$this->Groups->dashboard($group_id);

		$this->assertTrue(isset($this->Groups->viewVars['list']));
		$list = $this->Groups->viewVars['list'];

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
					'activity' => $list['members'][0]['activity'],
					'group_id' => 1,
					'project_id' => 0,
					'role_id' => 2,
					'role' => 'group.member',
					'image' => '/img/users/default_small.png',
				),
				array(
					'id' => 1,
					'name' => 'Test User',
					'username' => 'testuser',
					'session' => 'user:testuser',
					'token' => 'user:1',
					'type' => 'user',
					'activity' => $list['members'][1]['activity'],
					'group_id' => 1,
					'project_id' => 0,
					'role_id' => 1,
					'role' => 'group.manager',
					'image' => '/img/users/default_small.png',
				),
			),
		);
		$this->assertEqual($list, $expected);
	}

	function testDashboardDocuments()
	{
		$group_id = 1;
		$model = 'documents';

		$this->Groups->params = Router::parse('groups/dashboard/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->params['form']['model'] = $model;
		$this->Groups->dashboard($group_id);

		$this->assertTrue(isset($this->Groups->viewVars['list']));
		$list = $this->Groups->viewVars['list'];

		$expected = array(
			array(
				'id'  => 7,
				'parent_id' => null,
				'title' => 'Test Group - Private',
				'text' => 'Test Group - Private',
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
				'table_type' => 'group',
				'table_id' => 1,
				'shared' => 0,
				'expandable' => 1,
				'leaf' => null,
				'draggable' => null,
				'tags' => null,
			),
			array(
				'id'  => 8,
				'parent_id' => null,
				'title' => 'Test Group - Public',
				'text' => 'Test Group - Public',
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
				'table_type' => 'group',
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
		$group_id = 1;
		$model = 'url';

		$this->Groups->params = Router::parse('groups/dashboard/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->params['form']['model'] = $model;
		$this->Groups->dashboard($group_id);

		$this->assertTrue(isset($this->Groups->viewVars['list']));
		$list = $this->Groups->viewVars['list'];

		$expected = array(
			'success' => 1,
			'urls' => array(
				array(
					'id'  => 2,
					'table_type' => 'group',
					'table_id'  => 1,
					'link'  => 'http://example.com',
					'label'  => 'Test',
					'description' => 'Test',
					'privacy' => 0,
					'group' => 'Group: Private Test Group',
				),
			),
		);
		$this->assertEqual($list, $expected);
	}

	function testDashboardNullGroupId()
	{
		$group_id = null;
		$model = 'projects';

		$this->Groups->params = Router::parse('groups/dashboard/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->params['form']['model'] = $model;
		$this->Groups->dashboard($group_id);

		$this->assertEqual($this->Groups->error, 'missing_field');
	}

	function testDashboardInvalidGroupId()
	{
		$group_id = 'invalid';
		$model = 'projects';

		$this->Groups->params = Router::parse('groups/dashboard/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->params['form']['model'] = $model;
		$this->Groups->dashboard($group_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testDashboardInvalidGroupIdNotFound()
	{
		$group_id = 9000;
		$model = 'projects';

		$this->Groups->params = Router::parse('groups/dashboard/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->params['form']['model'] = $model;
		$this->Groups->dashboard($group_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testDashboardNullModel()
	{
		$group_id = 1;
		$model = null;

		$this->Groups->params = Router::parse('groups/dashboard/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->params['form']['model'] = $model;
		$this->Groups->dashboard($group_id);

		$this->assertEqual($this->Groups->error, 'missing_field');
	}

	function testDashboardInvalidModelNotFound()
	{
		$group_id = 1;
		$model = 'invalid';

		$this->Groups->params = Router::parse('groups/dashboard/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->params['form']['model'] = $model;
		$this->Groups->dashboard($group_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testDashboardInvalidModel()
	{
		$group_id = 1;
		$model = array(
			'invalid' => 'invalid',
		);

		$this->Groups->params = Router::parse('groups/dashboard/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->params['form']['model'] = $model;
		$this->Groups->dashboard($group_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testDashboardAccessDenied()
	{
		$group_id = 2;
		$model = 'projects';

		$this->Groups->params = Router::parse('groups/dashboard/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->params['form']['model'] = $model;
		$this->Groups->dashboard($group_id);

		$this->assertEqual($this->Groups->error, 'access_denied');
	}

	function testProfileNullGroupId()
	{
		$group_id = null;

		$this->Groups->params = Router::parse('groups/profile/' . $group_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->profile($group_id);

		$this->assertEqual($this->Groups->error, 'missing_field');
	}

	function testProfileInvalidGroupId()
	{
		$group_id = 'invalid';

		$this->Groups->params = Router::parse('groups/profile/' . $group_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->profile($group_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testProfileInvalidGroupIdNotFound()
	{
		$group_id = 9000;

		$this->Groups->params = Router::parse('groups/profile/' . $group_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->profile($group_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testProfileAccessDenied()
	{
		$group_id = 4;

		$this->Groups->params = Router::parse('groups/profile/' . $group_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->profile($group_id);

		$this->assertEqual($this->Groups->error, 'access_denied');
	}

	function testCreate()
	{
		$this->Groups->data = array(
			'Group' => array(
				'name' => 'Created Group',
				'description' => 'Created Group',
			),
		);

		$this->Groups->params = Router::parse('groups/create');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->create();

		$this->Groups->Ejabberd = new GroupsControllerMockEjabberdComponent();
		$this->Groups->Ejabberd->setReturnValue('create_room', true);
		$this->Groups->Ejabberd->setReturnValue('set_persistent', true);
		$this->Groups->Ejabberd->setReturnValue('set_logging', true);
		$this->Groups->Ejabberd->setReturnValue('srg_create', true);
		$this->Groups->Ejabberd->setReturnValue('srg_user_add', true);

		$conditions = array(
			'Group.name' => 'Created Group',
		);
		$this->Groups->Group->recursive = -1;
		$result = $this->Groups->Group->find('first', array('conditions' => $conditions));
		$this->assertFalse(empty($result));

		$expected = array(
			'Group' => array(
				'id' => $result['Group']['id'],
				'name' => 'Created Group',
				'email' => $result['Group']['email'],
				'description' => 'Created Group',
				'privacy' => 'private',
				'picture' => null,
				'created' => $result['Group']['created'],
			),
		);
		$this->assertEqual($result, $expected);

		$group_id = $result['Group']['id'];

		$conditions = array(
			'GroupsUsers.group_id' => $group_id,
			'GroupsUsers.user_id' => 1,
			'GroupsUsers.role_id' => $this->Groups->roles['group.manager'],
		);
		$this->Groups->GroupsUsers->recursive = -1;
		$result = $this->Groups->GroupsUsers->find('first', array('conditions' => $conditions));
		$this->assertFalse(empty($result));

		$expected = array(
			'GroupsUsers' => array(
				'id' => $result['GroupsUsers']['id'],
				'group_id' => $group_id,
				'user_id' => 1,
				'role_id' => $this->Groups->roles['group.manager'],
				'newrole_id' => $result['GroupsUsers']['newrole_id'],
			),
		);
		$this->assertEqual($result, $expected);

		$conditions = array(
			'Doc.table_type' => 'group',
			'Doc.table_id' => $group_id,
			'Doc.parent_id' => null,
		);
		$this->Groups->Doc->recursive = -1;
		$results = $this->Groups->Doc->find('all', array('conditions' => $conditions));
		$this->assertFalse(empty($results));

		$expected = array(
			array(
				'Doc' => array(
					'id'  => $results[0]['Doc']['id'],
					'table_type' => 'group',
					'table_id'  => $group_id,
					'parent_id'  => null,
					'lft'  => $results[0]['Doc']['lft'],
					'rght'  => $results[0]['Doc']['rght'],
					'filename'  => null,
					'title'  => 'Created Group - Private',
					'name'  => null,
					'path'  => '/Created Group - Private',
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
					'table_type' => 'group',
					'table_id'  => $group_id,
					'parent_id'  => null,
					'lft'  => $results[1]['Doc']['lft'],
					'rght'  => $results[1]['Doc']['rght'],
					'filename'  => null,
					'title'  => 'Created Group - Public',
					'name'  => null,
					'path'  => '/Created Group - Public',
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
			'Discussion.table_type' => 'group',
			'Discussion.table_id' => $group_id,
			'Discussion.parent_id' => null,
		);
		$this->Groups->Discussion->recursive = -1;
		$result = $this->Groups->Discussion->find('first', array('conditions' => $conditions));
		$this->assertFalse(empty($result));

		$expected = array(
			'Discussion' => array(
				'id'  => $result['Discussion']['id'],
				'table_id'  => $group_id,
				'table_type' => 'group',
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
			'Type.table_type' => 'group',
			'Type.table_id' => $group_id,
			'Type.parent_id' => null,
		);
		$this->Groups->Type->recursive = -1;
		$result = $this->Groups->Type->find('first', array('conditions' => $conditions));
		$this->assertFalse(empty($result));

		$expected = array(
			'Type' => array(
				'id'  => $result['Type']['id'],
				'table_type' => 'group',
				'table_id'  => $group_id,
				'name'  => 'Created Group',
				'shared'  => 0,
				'type' => 'root',
				'parent_id'  => null,
				'lft'  => $result['Type']['lft'],
				'rght'  => $result['Type']['rght'],
			),
		);
		$this->assertEqual($result, $expected);

		$conditions = array(
			'Note.table_type' => 'group',
			'Note.table_id' => $group_id,
			'Note.title' => 'Home',
		);
		$this->Groups->Note->recursive = -1;
		$result = $this->Groups->Note->find('first', array('conditions' => $conditions));
		$this->assertFalse(empty($result));

		$expected = array(
			'Note' => array(
				'id'  => $result['Note']['id'],
				'table_type' => 'group',
				'table_id'  => $group_id,
				'title'  => 'Home',
				'content'  => $result['Note']['content'],
				'created'  => $result['Note']['created'],
				'modified'  => $result['Note']['modified'],
				'permanent'  => 1,
				'read_only'  => 0
			),
		);
		$this->assertEqual($result, $expected);

		$this->assertEqual($this->Groups->redirectUrl, '/groups/invite/' . $group_id);
	}

	function testCreateInvalidData()
	{
		$this->Groups->data = array(
			'Group' => array(
				'name' => null,
				'description' => 'Created Group',
			),
		);

		$this->Groups->params = Router::parse('groups/create');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->Ejabberd = new GroupsControllerMockEjabberdComponent();
		$this->Groups->Ejabberd->setReturnValue('create_room', true);
		$this->Groups->Ejabberd->setReturnValue('set_persistent', true);
		$this->Groups->Ejabberd->setReturnValue('set_logging', true);
		$this->Groups->Ejabberd->setReturnValue('srg_create', true);
		$this->Groups->Ejabberd->setReturnValue('srg_user_add', true);

		$this->Groups->create();

		$this->assertEqual($this->Groups->error, 'internal_error');
	}

	function testEdit()
	{
		$group_id = 1;

		$this->Groups->data = array(
			'Group' => array(
				'name' => 'Edited Group',
				'description' => 'Edited Description',
				'email' => 'edited@example.com',
				'interests' => 'Edited Group 1',
				'privacy' => 'public',
				'picture' => array(
					'name' => null,
					'type' => null,
					'tmp_name' => null,
					'error' => 4,
					'size' => 0,
				),
			),
			'GroupsAddress' => array(
				'10' => array(
					'label' => 'Work',
					'address1' => '212 W. 10th Street',
					'address2' => 'Suite A-470',
					'city' => 'Indianapolis',
					'state' => 'IN',
					'zip_code' => 46202,
					'country' => 'USA',
				),
			),
			'GroupsPhone' => array(
				'12' => array(
					'label' => 'Work',
					'phone_number' => '(317) 489-6818',
				),
			),
			'GroupsUrl' => array(
				'17' => array(
					'label' => 'Google',
					'link' => 'http://www.google.com',
				),
			),
			'GroupsAssociation' => array(
				'ext-1' => array(
					'label' => 'Test Association',	
					'association' => 'Test Institution',
					'role' => 'Tester',
				),
			),
			'GroupsAward' => array(
				'9' => array(
					'label' => 'Test',
					'award' => 'Test',
				),
			),
		);

		$this->Groups->params = Router::parse('groups/edit/' . $group_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->Ejabberd = new GroupsControllerMockEjabberdComponent();
		$this->Groups->Ejabberd->setReturnValue('srg_set_name', true);
		$this->Groups->Ejabberd->setReturnValue('srg_set_description', true);

		$this->Groups->FileCmp = new GroupsControllerMockFileCmpComponent();
		$this->Groups->FileCmp->setReturnValue('is_uploaded_file', true);
		$this->Groups->FileCmp->setReturnValue('mimetype', 'image/png');
		$this->Groups->FileCmp->setReturnValue('save', 100);
		$this->Groups->FileCmp->setReturnValue('remove', true);
		$this->Groups->FileCmp->setReturnValue('exists', false);

		$this->Groups->Image = new GroupsControllerMockImageComponent();
		$this->Groups->Image->setReturnValue('scale', 'picture data');
		$this->Groups->Image->setReturnValue('crop', 'picture data');

		$this->Groups->edit($group_id);

		$conditions = array(
			'Group.id' => $group_id,
		);
		$this->Groups->Group->recursive = -1;
		$result = $this->Groups->Group->find('first', array('conditions' => $conditions));
		$this->assertFalse(empty($result));

		$expected = array(
			'Group' => array(
				'id' => $group_id,
				'name' => 'Edited Group',
				'email' => 'edited@example.com',
				'description' => 'Edited Description',
				'privacy' => 'public',
				'picture' => $result['Group']['picture'],
				'created' => $result['Group']['created'],
			),
		);
		$this->assertEqual($result, $expected);

		$conditions = array(
			'GroupsAddress.group_id' => $group_id,
		);
		$this->Groups->GroupsAddress->recursive = -1;
		$results = $this->Groups->GroupsAddress->find('all', array('conditions' => $conditions));
		$this->assertFalse(empty($results));

		$expected = array(
			array(
				'GroupsAddress' => array(
					'id'  => $results[0]['GroupsAddress']['id'],
					'group_id'  => $group_id,
					'address1'  => '212 W. 10th Street',
					'address2'  => 'Suite A-470',
					'city'  => 'Indianapolis',
					'state'  => 'IN',
					'country'  => 'USA',
					'zip_code'  => '46202',
					'longitude'  => $results[0]['GroupsAddress']['longitude'],
					'latitude'  => $results[0]['GroupsAddress']['latitude'],
					'label'  => 'Work',
					'privacy'  => 0,
				),
			),
		);
		$this->assertEqual($results, $expected);

		$conditions = array(
			'GroupsPhone.group_id' => $group_id,
		);
		$this->Groups->GroupsPhone->recursive = -1;
		$results = $this->Groups->GroupsPhone->find('all', array('conditions' => $conditions));
		$this->assertFalse(empty($results));

		$expected = array(
			array(
				'GroupsPhone' => array(
					'id' => $results[0]['GroupsPhone']['id'],
					'group_id' => $group_id,
					'phone_number' => '(317) 489-6818',
					'label' => 'Work',
					'privacy' => 0,
				),
			),
		);
		$this->assertEqual($results, $expected);

		$conditions = array(
			'GroupsUrl.group_id' => $group_id,
		);
		$this->Groups->GroupsUrl->recursive = -1;
		$results = $this->Groups->GroupsUrl->find('all', array('conditions' => $conditions));
		$this->assertFalse(empty($results));

		$expected = array(
			array(
				'GroupsUrl' => array(
					'id' => $results[0]['GroupsUrl']['id'],
					'group_id' => $group_id,
					'link' => 'http://www.google.com',
					'label' => 'Google',
					'privacy' => 0,
				),
			),
		);
		$this->assertEqual($results, $expected);

		$conditions = array(
			'GroupsAssociation.group_id' => $group_id,
		);
		$this->Groups->GroupsAssociation->recursive = -1;
		$results = $this->Groups->GroupsAssociation->find('all', array('conditions' => $conditions));
		$this->assertFalse(empty($results));

		$expected = array(
			array(
				'GroupsAssociation' => array(
					'id' => $results[0]['GroupsAssociation']['id'],
					'group_id' => $group_id,
					'label' => 'Test Association',
					'association' => 'Test Institution',
					'role' => 'Tester',
					'privacy' => 0,
				),
			),
		);
		$this->assertEqual($results, $expected);

		$conditions = array(
			'GroupsAward.group_id' => $group_id,
		);
		$this->Groups->GroupsAward->recursive = -1;
		$results = $this->Groups->GroupsAward->find('all', array('conditions' => $conditions));
		$this->assertFalse(empty($results));

		$expected = array(
			array(
				'GroupsAward' => array(
					'id' => $results[0]['GroupsAward']['id'],
					'group_id' => $group_id,
					'label' => 'Test',
					'award' => 'Test',
					'privacy' => null,
				),
			),
		);
		$this->assertEqual($results, $expected);

		$conditions = array(
			'Interest.keyword' => 'editedgroup1',
		);
		$this->Groups->Interest->recursive = -1;
		$result = $this->Groups->Interest->find('first', array('conditions' => $conditions));
		$this->assertFalse(empty($result));

		$interest_id = $result['Interest']['id'];

		$expected = array(
			'Interest' => array(
				'id' => $result['Interest']['id'],
				'keyword' => 'editedgroup1',
				'name' => 'Edited Group 1',
			),
		);
		$this->assertEqual($result, $expected);

		$conditions = array(
			'GroupsInterest.group_id' => $group_id,
		);
		$this->Groups->GroupsInterest->recursive = -1;
		$results = $this->Groups->GroupsInterest->find('all', array('conditions' => $conditions));
		$this->assertFalse(empty($results));

		$expected = array(
			array(
				'GroupsInterest' => array(
					'id' => $results[0]['GroupsInterest']['id'],
					'group_id' => $group_id,
					'interest_id' => $interest_id,
					'privacy' => 0,
				),
			),
		);
		$this->assertEqual($results, $expected);

		$conditions = array(
			'Doc.table_type' => 'group',
			'Doc.table_id' => $group_id,
			'Doc.parent_id' => null,
		);
		$this->Groups->Doc->recursive = -1;
		$results = $this->Groups->Doc->find('all', array('conditions' => $conditions));
		$this->assertFalse(empty($results));

		$expected = array(
			array(
				'Doc' => array(
					'id'  => $results[0]['Doc']['id'],
					'table_type' => 'group',
					'table_id'  => $group_id,
					'parent_id'  => null,
					'lft'  => $results[0]['Doc']['lft'],
					'rght'  => $results[0]['Doc']['rght'],
					'filename'  => null,
					'title'  => 'Edited Group - Private',
					'name'  => null,
					'path'  => '/Edited Group - Private',
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
					'table_type' => 'group',
					'table_id'  => $group_id,
					'parent_id'  => null,
					'lft'  => $results[1]['Doc']['lft'],
					'rght'  => $results[1]['Doc']['rght'],
					'filename'  => null,
					'title'  => 'Edited Group - Public',
					'name'  => null,
					'path'  => '/Edited Group - Public',
					'author_id'  => 1,
					'description'  => null,
					'created'  => $results[0]['Doc']['created'],
					'modified'  => $results[0]['Doc']['modified'],
					'status' => 'in',
					'current_user_id'  => null,
					'shared'  => 1,
					'type' => 'folder'
				),
			),
		);
		$this->assertEqual($results, $expected);

		$this->assertEqual($this->Groups->redirectUrl, '/groups/edit/' . $group_id);
	}

	function testEditJson()
	{
		$group_id = 1;

		$this->Groups->params = Router::parse('groups/edit/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->edit($group_id);

		$this->assertTrue(isset($this->Groups->viewVars['node']));
		$node = $this->Groups->viewVars['node'];

		$expected = array(
			'Group' => array(
				'id' => 1,
				'email' => 'testgrp+private@example.com',
				'name' => 'Private Test Group',
				'description' => 'Test Group',
				'privacy' => 'private',
				'interests' => 'Test Test',
			),
			'GroupsPhone' => array(
				array(
					'id' => 1,
					'group_id' => 1,
					'phone_number' => '1-317-489-6818',
					'label' => 'Test',
					'privacy' => 0,
				),
			),
			'GroupsUrl' => array(
				array(
					'id' => 1,
					'group_id' => 1,
					'link' => 'http://example.com',
					'label' => 'Test',
					'privacy' => 0,
				),
			),
			'GroupsAssociation' => array(
				array(
					'id' => 1,
					'group_id' => 1,
					'label' => 'Test',
					'association' => 'Test',
					'role' => 'Test',
					'privacy' => 0,
				),
			),
			'GroupsAward' => array(
				array(
					'id' => 1,
					'group_id' => 1,
					'label' => 'Test',
					'award' => 'Test',
					'privacy' => 0,
				),
			),
			'GroupsAddress' => array(
				array(
					'id' => 1,
					'group_id' => 1,
					'address1' => '212 W 10th St',
					'address2' => 'Suite A470',
					'city' => 'Indianapolis',
					'state' => 'IN',
					'country' => 'USA',
					'zip_code' => 46202,
					'longitude' => $node['GroupsAddress']['0']['longitude'],
					'latitude' => $node['GroupsAddress']['0']['latitude'],
					'label' => 'Test',
					'privacy' => 0,
				),
			),
			'User' => array(
				array(
					'id' => 2,
					'name' => 'Another User',
					'GroupsUsers' => array(
						'id' => 2,
						'group_id' => 1,
						'user_id' => 2,
						'role_id' => 2,
						'newrole_id' => 3,
					),
				),
				array(
					'id' => 1,
					'name' => 'Test User',
					'GroupsUsers' => array(
						'id' => 1,
						'group_id' => 1,
						'user_id' => 1,
						'role_id' => 1,
						'newrole_id' => 2,
					),
				),
			),
			'Interest' => array(
				array(
					'id' => 1,
					'keyword' => 'testtest',
					'name' => 'Test Test',
					'GroupsInterest' => array(
						'id' => 1,
						'group_id' => 1,
						'interest_id' => 1,
						'privacy' => 0,
					),
				),
			),
		);

		$this->assertEqual($node, $expected);
	}

	function testEditNullGroupId()
	{
		$group_id = null;

		$this->Groups->params = Router::parse('groups/edit/' . $group_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->edit($group_id);

		$this->assertEqual($this->Groups->error, 'missing_field');
	}

	function testEditInvalidGroupId()
	{
		$group_id = 'invalid';

		$this->Groups->params = Router::parse('groups/edit/' . $group_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->edit($group_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testEditInvalidGroupIdNotFound()
	{
		$group_id = 9000;

		$this->Groups->params = Router::parse('groups/edit/' . $group_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->edit($group_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testEditInvalidData()
	{
		$group_id = 1;

		$this->Groups->data = array(
			'Group' => array(
				'name' => null,
				'description' => 'Edited Description',
				'email' => 'edited@example.com',
				'interests' => 'Edited Group 1',
				'privacy' => 'public',
				'picture' => null, 
			),
		);

		$this->Groups->params = Router::parse('groups/edit/' . $group_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->edit($group_id);

		$this->assertEqual($this->Groups->error, 'internal_error');
	}

	function testEditAccessDenied()
	{
		$group_id = 2;

		$this->Groups->params = Router::parse('groups/edit/' . $group_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->edit($group_id);

		$this->assertEqual($this->Groups->error, 'access_denied');
	}

	function testDelete()
	{
		$group_id = 1;

		$this->Groups->params = Router::parse('groups/delete/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->Ejabberd = new GroupsControllerMockEjabberdComponent();
		$this->Groups->Ejabberd->setReturnValue('destroy_room', true);
		$this->Groups->Ejabberd->setReturnValue('srg_delete', true);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->delete($group_id);

		$this->assertTrue(isset($this->Groups->viewVars['response']));
		$response = $this->Groups->viewVars['response'];
		$this->assertTrue($response['success']);

		$conditions = array(
			'Group.id' => $group_id,
		);
		$this->Groups->Group->recursive = -1;
		$result = $this->Groups->Group->find('first', array('conditions' => $conditions));
		$this->assertTrue(empty($result));
	}

	function testDeleteNotJson()
	{
		$group_id = 1;

		$this->Groups->params = Router::parse('groups/delete/' . $group_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', false);

		$this->Groups->delete($group_id);

		$this->assertEqual($this->Groups->error, 'error404');
	}

	function testDeleteNullGroupId()
	{
		$group_id = null;

		$this->Groups->params = Router::parse('groups/delete/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->delete($group_id);

		$this->assertEqual($this->Groups->error, 'missing_field');
	}

	function testDeleteInvalidGroupId()
	{
		$group_id = 'invalid';

		$this->Groups->params = Router::parse('groups/delete/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->delete($group_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testDeleteInvalidGroupIdNotFound()
	{
		$group_id = 9000;

		$this->Groups->params = Router::parse('groups/delete/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->delete($group_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testDeleteAccessDenied()
	{
		$group_id = 2;

		$this->Groups->params = Router::parse('groups/delete/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->delete($group_id);

		$this->assertEqual($this->Groups->error, 'access_denied');
	}

	/* causing fatal error in test

	function testRemoveUser()
	{
		$group_id = 1;
		$user_id = 2;

		$this->Groups->params = Router::parse('groups/removeuser/' . $group_id . '/' . $user_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->Ejabberd = new GroupsControllerMockEjabberdComponent();
		$this->Groups->Ejabberd->setReturnValue('srg_user_del', true);

		$this->Groups->removeuser($group_id, $user_id);

		$this->assertTrue(isset($this->Groups->viewVars['response']));
		$response = $this->Groups->viewVars['response'];
		$this->assertTrue($response['success']);

		$conditions = array(
			'GroupsUsers.group_id' => $group_id,
			'GroupsUsers.user_id' => $user_id,
		);	
		$this->Groups->GroupsUsers->recursive = -1;
		$results = $this->Groups->GroupsUsers->find('all', array('conditions' => $conditions));
		$this->assertTrue(empty($results));
	} */

	function testRemoveUserNotJson()
	{
		$group_id = 1;
		$user_id = 2;

		$this->Groups->params = Router::parse('groups/removeuser/' . $group_id . '/' . $user_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', false);

		$this->Groups->removeuser($group_id, $user_id);

		$this->assertEqual($this->Groups->error, 'error404');
	}

	function testRemoveUserNullGroupId()
	{
		$group_id = null;
		$user_id = 2;

		$this->Groups->params = Router::parse('groups/removeuser/' . $group_id . '/' . $user_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->removeuser($group_id, $user_id);

		$this->assertEqual($this->Groups->error, 'missing_field');
	}	

	function testRemoveUserInvalidGroupId()
	{
		$group_id = 'invalid';
		$user_id = 2;

		$this->Groups->params = Router::parse('groups/removeuser/' . $group_id . '/' . $user_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->removeuser($group_id, $user_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testRemoveUserInvalidGroupIdNotFound()
	{
		$group_id = 9000;
		$user_id = 2;

		$this->Groups->params = Router::parse('groups/removeuser/' . $group_id . '/' . $user_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->removeuser($group_id, $user_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testRemoveUserNullUserId()
	{
		$group_id = 1;
		$user_id = null;

		$this->Groups->params = Router::parse('groups/removeuser/' . $group_id . '/' . $user_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->removeuser($group_id, $user_id);

		$this->assertEqual($this->Groups->error, 'missing_field');
	}

	function testRemoveUserInvalidUserId()
	{
		$group_id = 1;
		$user_id = 'invalid';

		$this->Groups->params = Router::parse('groups/removeuser/' . $group_id . '/' . $user_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->removeuser($group_id, $user_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testRemoveUserInvalidUserIdNotFound()
	{
		$group_id = 1;
		$user_id = 9000;

		$this->Groups->params = Router::parse('groups/removeuser/' . $group_id . '/' . $user_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->removeuser($group_id, $user_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testRemoveUserInvalidUserIdSelf()
	{
		$group_id = 1;
		$user_id = 1;

		$this->Groups->params = Router::parse('groups/removeuser/' . $group_id . '/' . $user_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->removeuser($group_id, $user_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testRemoveUserInvalidUserIdNotInGroup()
	{
		$group_id = 1;
		$user_id = 4;

		$this->Groups->params = Router::parse('groups/removeuser/' . $group_id . '/' . $user_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->removeuser($group_id, $user_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testRemoveUserAccessDenied()
	{
		$group_id = 2;
		$user_id = 2;

		$this->Groups->params = Router::parse('groups/removeuser/' . $group_id . '/' . $user_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->removeuser($group_id, $user_id);

		$this->assertEqual($this->Groups->error, 'access_denied');
	}
/*
	function testInvite()
	{
		$group_id = 1;
		$invite_email = 'does-not-exist@example.com';
		$invite_users = 'user:3';
		$add_email = 'fifth@example.com, sixth@example.com';

		$this->Groups->params = Router::parse('groups/invite/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		/*
		 * We can't mock up the MessagingComponent because
		 * it relies on several other components that
		 * wouldn't be loaded. Instead, we extend the
		 * messaging component with our custom messaging
		 * component, and replace it here.
		 */
/*		$this->Groups->Messaging = $this->Groups->GroupsControllerTestMessaging;

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->params['form']['invite_email'] = $invite_email;
		$this->Groups->params['form']['invite_users'] = $invite_users;
		$this->Groups->params['form']['add_email'] = $add_email;
		$this->Groups->invite($group_id);

		$this->assertTrue(isset($this->Groups->viewVars['response']));
		$response = $this->Groups->viewVars['response'];
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
		$group_id = 1;
		$action = 'colleagues';

		$this->Groups->params = Router::parse('groups/invite/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->params['form']['action'] = $action;
		$this->Groups->invite($group_id);

		$this->assertTrue(isset($this->Groups->viewVars['response']));
		$response = $this->Groups->viewVars['response'];
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
		$group_id = 1;
		$action = 'pending';

		$this->Groups->params = Router::parse('groups/invite/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->params['form']['action'] = $action;
		$this->Groups->invite($group_id);

		$this->assertTrue(isset($this->Groups->viewVars['response']));
		$response = $this->Groups->viewVars['response'];
		$this->assertTrue($response['success']);

		$expected = array(
			array(
				'user:1',
				'Test User',
			),
		);
		$this->assertEqual($response['pending'], $expected);
	}

	function testInviteSearch()
	{
		$group_id = 1;
		$action = 'search';
		$query = 'third';

		$this->Groups->params = Router::parse('groups/invite/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->params['form']['action'] = $action;
		$this->Groups->params['form']['query'] = $query;
		$this->Groups->invite($group_id);

		$this->assertTrue(isset($this->Groups->viewVars['response']));
		$response = $this->Groups->viewVars['response'];
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
		$group_id = 1;
		$add_email = 'newuser@example.com';

		$this->Groups->params = Router::parse('groups/invite/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->params['form']['add_email'] = $add_email;
		$this->Groups->invite($group_id);

		$this->assertTrue(isset($this->Groups->viewVars['response']));
		$response = $this->Groups->viewVars['response'];
		$this->assertTrue($response['success']);

		$username = array_shift(explode('@', $add_email));

		$conditions = array(
			'User.username' => $username,
			'User.email' => $add_email,
			'User.name' => $username,
		);
		$result = $this->Groups->User->find('first', array('conditions' => $conditions));

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

	function testInviteNullGroupId()
	{
		$group_id = null;
		$email = 'fourthuser@example.com';

		$this->Groups->params = Router::parse('groups/invite/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->params['form']['email'] = $email;
		$this->Groups->invite($group_id);

		$this->assertEqual($this->Groups->error, 'missing_field');
	}	

	function testInviteInvalidGroupId()
	{
		$group_id = 'invalid';
		$email = 'fourthuser@example.com';

		$this->Groups->params = Router::parse('groups/invite/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->params['form']['email'] = $email;
		$this->Groups->invite($group_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testInviteInvalidGroupIdNotFound()
	{
		$group_id = 9000;
		$email = 'fourthuser@example.com';

		$this->Groups->params = Router::parse('groups/invite/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->params['form']['email'] = $email;
		$this->Groups->invite($group_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testInviteAccessDenied()
	{
		$group_id = 2;
		$email = 'fourthuser@example.com';

		$this->Groups->params = Router::parse('groups/invite/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->params['form']['email'] = $email;
		$this->Groups->invite($group_id);

		$this->assertEqual($this->Groups->error, 'access_denied');
	}

	function testInviteInvalidInviteEmail()
	{
		$group_id = 1;
		$invite_email = 'invalid';

		$this->Groups->params = Router::parse('groups/invite/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->params['form']['invite_email'] = $invite_email;
		$this->Groups->invite($group_id);

		$this->assertTrue(isset($this->Groups->viewVars['response']));
		$response = $this->Groups->viewVars['response'];
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
		$group_id = 1;
		$invite_users = 'invalid';

		$this->Groups->params = Router::parse('groups/invite/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->params['form']['invite_users'] = $invite_users;
		$this->Groups->invite($group_id);

		$this->assertTrue(isset($this->Groups->viewVars['response']));
		$response = $this->Groups->viewVars['response'];
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
		$group_id = 1;
		$action = 'invalid';

		$this->Groups->params = Router::parse('groups/invite/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->params['form']['action'] = $action;
		$this->Groups->invite($group_id);

		$this->assertTrue(isset($this->Groups->viewVars['response']));
		$response = $this->Groups->viewVars['response'];
		$this->assertFalse($response['success']);
	}

	function testInviteInvalidQuery()
	{
		$group_id = 1;
		$action = 'search';
		$query = '#NOTFOUND#';

		$this->Groups->params = Router::parse('groups/invite/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->params['form']['action'] = $action;
		$this->Groups->params['form']['query'] = $query;
		$this->Groups->invite($group_id);

		$this->assertTrue(isset($this->Groups->viewVars['response']));
		$response = $this->Groups->viewVars['response'];
		$this->assertTrue($response['success']);

		$this->assertTrue(empty($response['result']));
	}

	function testAccept()
	{
		$group_id = 2;
		$inbox_id = 3;

		$this->Groups->params = Router::parse('groups/accept/' . $group_id . '/' . $inbox_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->Ejabberd = new GroupsControllerMockEjabberdComponent();
		$this->Groups->Ejabberd->setReturnValue('srg_user_add', true);

		$this->Groups->accept($group_id, $inbox_id);

		$this->assertEqual($this->Groups->redirectUrl, '/groups/dashboard/' . $group_id);
	}

	function testAcceptNullGroupId()
	{
		$group_id = null;
		$inbox_id = 3;

		$this->Groups->params = Router::parse('groups/accept/' . $group_id . '/' . $inbox_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->accept($group_id, $inbox_id);

		$this->assertEqual($this->Groups->error, 'missing_field');
	}

	function testAcceptInvalidGroupId()
	{
		$group_id = 'invalid';
		$inbox_id = 3;

		$this->Groups->params = Router::parse('groups/accept/' . $group_id . '/' . $inbox_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->accept($group_id, $inbox_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testAcceptNullInboxId()
	{
		$group_id = 2;
		$inbox_id = null;

		$this->Groups->params = Router::parse('groups/accept/' . $group_id . '/' . $inbox_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->accept($group_id, $inbox_id);

		$this->assertEqual($this->Groups->error, 'missing_field');
	}

	function testAcceptInvalidInboxId()
	{
		$group_id = 2;
		$inbox_id = 'invalid';

		$this->Groups->params = Router::parse('groups/accept/' . $group_id . '/' . $inbox_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->accept($group_id, $inbox_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testAcceptInvalidGroupNotFound()
	{
		$group_id = 9000;
		$inbox_id = 3;

		$this->Groups->params = Router::parse('groups/accept/' . $group_id . '/' . $inbox_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->accept($group_id, $inbox_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testAcceptInboxIdNotFound()
	{
		$group_id = 2;
		$inbox_id = 9000;

		$this->Groups->params = Router::parse('groups/accept/' . $group_id . '/' . $inbox_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->accept($group_id, $inbox_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	// Person viewing invite message is not the recipient.
	function testAcceptAccessDenied()
	{
		$group_id = 1;
		$inbox_id = 3;

		$this->Groups->Session->write('Auth.User', array(
			'id' => 3,
			'username' => 'thirduser',
			'changepass' => 0,
			'email' => 'thirduser@example.com',
		));

		$this->Groups->params = Router::parse('groups/accept/' . $group_id . '/' . $inbox_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->accept($group_id, $inbox_id);

		$this->assertEqual($this->Groups->error, 'access_denied');
	}

	// Invite where invitee is already a member of the group.
	function testAcceptInvalidInviteRelationshipAlreadyExists()
	{
		$group_id = 1;
		$inbox_id = 4;

		$this->Groups->params = Router::parse('groups/accept/' . $group_id . '/' . $inbox_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->accept($group_id, $inbox_id);

		$this->assertEqual($this->Groups->error, 'internal_error');
	}

	// Join request where person viewing the request is not a manager of the group.
	function testAcceptAccessDeniedGroupRequest()
	{
		$group_id = 1;
		$inbox_id = 5;

		$this->Groups->Session->write('Auth.User', array(
			'id' => 3,
			'username' => 'thirduser',
			'changepass' => 0,
			'email' => 'thirduser@example.com',
		));

		$this->Groups->params = Router::parse('groups/accept/' . $group_id . '/' . $inbox_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->accept($group_id, $inbox_id);

		$this->assertEqual($this->Groups->error, 'access_denied');
	}

	// Join request where the person who made the request is already a member of the group.
	function testAcceptInvalidRequestRelationshipAlreadyExists()
	{
		$group_id = 1;
		$inbox_id = 6;

		$this->Groups->params = Router::parse('groups/accept/' . $group_id . '/' . $inbox_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->accept($group_id, $inbox_id);

		$this->assertEqual($this->Groups->error, 'internal_error');
	}

	// Message is not a request or invitation.
	function testAcceptInvalidTemplate()
	{
		$group_id = 1;
		$inbox_id = 7;

		$this->Groups->params = Router::parse('groups/accept/' . $group_id . '/' . $inbox_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->accept($group_id, $inbox_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}
*/
	function testLeave()
	{
		$group_id = 1;

		$this->Groups->Session->write('Auth.User', array(
			'id' => 2,
			'username' => 'anotheruser',
			'changepass' => 0,
			'email' => 'anotheruser@example.com',
		));

		$this->Groups->params = Router::parse('groups/leave/' . $group_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->Ejabberd = new GroupsControllerMockEjabberdComponent();
		$this->Groups->Ejabberd->setReturnValue('srg_user_del', true);

		$this->Groups->leave($group_id);

		$this->assertEqual($this->Groups->redirectUrl, '/groups/user/' . $this->Groups->Session->read('Auth.User.id'));
	}

	function testLeaveNullGroupId()
	{
		$group_id = null;

		$this->Groups->params = Router::parse('groups/leave/' . $group_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->leave($group_id);

		$this->assertEqual($this->Groups->error, 'missing_field');
	}

	function testLeaveInvalidGroupId()
	{
		$group_id = 'invalid';

		$this->Groups->params = Router::parse('groups/leave/' . $group_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->leave($group_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testLeaveInvalidGroupNotFound()
	{
		$group_id = 9000;

		$this->Groups->params = Router::parse('groups/leave/' . $group_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->leave($group_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	// The last group manager attempts to leave the group. 
	function testLeaveLastManager()
	{
		$group_id = 1;

		$this->Groups->params = Router::parse('groups/leave/' . $group_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->leave($group_id);

		$this->assertEqual($this->Groups->error, 'internal_error');
	}

	function testLeaveInvalidPersonNotInGroup()
	{
		$group_id = 1;

		$this->Groups->Session->write('Auth.User', array(
			'id' => 3,
			'username' => 'thirduser',
			'changepass' => 0,
			'email' => 'thirduser@example.com',
		));

		$this->Groups->params = Router::parse('groups/leave/' . $group_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->leave($group_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testPromote()
	{
		$group_id = 1;
		$user_id = 2;

		$this->Groups->params = Router::parse('groups/promote/' . $group_id . '/' . $user_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->promote($group_id, $user_id);

		$this->assertTrue(isset($this->Groups->viewVars['response']));
		$response = $this->Groups->viewVars['response'];
		$this->assertTrue($response['success']);

		$conditions = array(
			'GroupsUsers.group_id' => $group_id,
			'GroupsUsers.user_id' => $user_id,
		);
		$this->Groups->GroupsUsers->recursive = -1;
		$result = $this->Groups->GroupsUsers->find('first', array('conditions' => $conditions));
		$this->assertFalse(empty($result));

		$this->assertEqual($result['GroupsUsers']['role_id'], $this->Groups->roles['group.manager']);
	}

	function testPromoteNotJson()
	{
		$group_id = 1;
		$user_id = 2;

		$this->Groups->params = Router::parse('groups/promote/' . $group_id . '/' . $user_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', false);

		$this->Groups->promote($group_id, $user_id);

		$this->assertEqual($this->Groups->error, 'error404');
	}

	function testPromoteNullGroupId()
	{
		$group_id = null;
		$user_id = 2;

		$this->Groups->params = Router::parse('groups/promote/' . $group_id . '/' . $user_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->promote($group_id, $user_id);

		$this->assertEqual($this->Groups->error, 'missing_field');
	}

	function testPromoteInvalidGroupId()
	{
		$group_id = 'invalid';
		$user_id = 2;

		$this->Groups->params = Router::parse('groups/promote/' . $group_id . '/' . $user_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->promote($group_id, $user_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testPromoteInvalidGroupIdNotFound()
	{
		$group_id = 9000;
		$user_id = 2;

		$this->Groups->params = Router::parse('groups/promote/' . $group_id . '/' . $user_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->promote($group_id, $user_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testPromoteNullUserId()
	{
		$group_id = 1;
		$user_id = null;

		$this->Groups->params = Router::parse('groups/promote/' . $group_id . '/' . $user_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->promote($group_id, $user_id);

		$this->assertEqual($this->Groups->error, 'missing_field');
	}

	function testPromoteInvalidUserId()
	{
		$group_id = 1;
		$user_id = 'invalid';

		$this->Groups->params = Router::parse('groups/promote/' . $group_id . '/' . $user_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->promote($group_id, $user_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testPromoteInvalidUserIdNotFound()
	{
		$group_id = 1;
		$user_id = 9000;

		$this->Groups->params = Router::parse('groups/promote/' . $group_id . '/' . $user_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->promote($group_id, $user_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testPromoteAccessDenied()
	{
		$group_id = 2;
		$user_id = 2;

		$this->Groups->params = Router::parse('groups/promote/' . $group_id . '/' . $user_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->promote($group_id, $user_id);

		$this->assertEqual($this->Groups->error, 'access_denied');
	}

	function testPromoteInvalidRelationship()
	{
		$group_id = 1;
		$user_id = 3;

		$this->Groups->params = Router::parse('groups/promote/' . $group_id . '/' . $user_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->promote($group_id, $user_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testPromoteInvalidRoleNotFound()
	{
		$group_id = 3;
		$user_id = 3;

		$this->Groups->Session->write('Auth.User', array(
			'id' => 2,
			'username' => 'anotheruser',
			'changepass' => 0,
			'email' => 'anotheruser@example.com',
		));

		$this->Groups->params = Router::parse('groups/promote/' . $group_id . '/' . $user_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->promote($group_id, $user_id);

		$this->assertEqual($this->Groups->error, 'internal_error');
	}

	function testDemote()
	{
		$group_id = 3;
		$user_id = 2;

		$this->Groups->params = Router::parse('groups/demote/' . $group_id . '/' . $user_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->demote($group_id, $user_id);

		$this->assertTrue(isset($this->Groups->viewVars['response']));
		$response = $this->Groups->viewVars['response'];
		$this->assertTrue($response['success']);

		$conditions = array(
			'GroupsUsers.group_id' => $group_id,
			'GroupsUsers.user_id' => $user_id,
		);
		$this->Groups->GroupsUsers->recursive = -1;
		$result = $this->Groups->GroupsUsers->find('first', array('conditions' => $conditions));
		$this->assertFalse(empty($result));

		$this->assertEqual($result['GroupsUsers']['role_id'], $this->Groups->roles['group.member']);
	}

	function testDemoteNotJson()
	{
		$group_id = 3;
		$user_id = 2;

		$this->Groups->params = Router::parse('groups/demote/' . $group_id . '/' . $user_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', false);

		$this->Groups->demote($group_id, $user_id);

		$this->assertEqual($this->Groups->error, 'error404');
	}

	function testDemoteNullGroupId()
	{
		$group_id = null;
		$user_id = 2;

		$this->Groups->params = Router::parse('groups/demote/' . $group_id . '/' . $user_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->demote($group_id, $user_id);

		$this->assertEqual($this->Groups->error, 'missing_field');
	}

	function testDemoteInvalidGroupId()
	{
		$group_id = 'invalid';
		$user_id = 2;

		$this->Groups->params = Router::parse('groups/demote/' . $group_id . '/' . $user_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->demote($group_id, $user_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testDemoteInvalidGroupIdNotFound()
	{
		$group_id = 9000;
		$user_id = 2;

		$this->Groups->params = Router::parse('groups/demote/' . $group_id . '/' . $user_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->demote($group_id, $user_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testDemoteNullUserId()
	{
		$group_id = 3;
		$user_id = null;

		$this->Groups->params = Router::parse('groups/demote/' . $group_id . '/' . $user_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->demote($group_id, $user_id);

		$this->assertEqual($this->Groups->error, 'missing_field');
	}

	function testDemoteInvalidUserId()
	{
		$group_id = 3;
		$user_id = 'invalid';

		$this->Groups->params = Router::parse('groups/demote/' . $group_id . '/' . $user_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->demote($group_id, $user_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testDemoteInvalidUserIdNotFound()
	{
		$group_id = 3;
		$user_id = 9000;

		$this->Groups->params = Router::parse('groups/demote/' . $group_id . '/' . $user_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->demote($group_id, $user_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testDemoteInvalidUserIdSelf()
	{
		$group_id = 1;
		$user_id = 1;

		$this->Groups->params = Router::parse('groups/demote/' . $group_id . '/' . $user_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->demote($group_id, $user_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testDemoteAccessDenied()
	{
		$group_id = 2;
		$user_id = 2;

		$this->Groups->params = Router::parse('groups/demote/' . $group_id . '/' . $user_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->demote($group_id, $user_id);

		$this->assertEqual($this->Groups->error, 'access_denied');
	}

	function testDemoteInvalidRelationship()
	{
		$group_id = 1;
		$user_id = 3;

		$this->Groups->params = Router::parse('groups/demote/' . $group_id . '/' . $user_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->demote($group_id, $user_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testDemoteInvalidRoleNotFound()
	{
		$group_id = 3;
		$user_id = 3;

		$this->Groups->Session->write('Auth.User', array(
			'id' => 2,
			'username' => 'anotheruser',
			'changepass' => 0,
			'email' => 'anotheruser@example.com',
		));

		$this->Groups->params = Router::parse('groups/demote/' . $group_id . '/' . $user_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->demote($group_id, $user_id);

		$this->assertEqual($this->Groups->error, 'internal_error');
	}

	function testUser()
	{
		$this->Groups->params = Router::parse('groups/user.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->user();

		$this->assertTrue(isset($this->Groups->viewVars['nodes']));
		$nodes = $this->Groups->viewVars['nodes'];

		$expected = array(
			'success' => 1,
			'groups' => array(
				array(
					'id'  => 1,
					'name' => 'Private Test Group',
					'text' => 'Private Test Group',
					'leaf' => true,
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
				array(
					'id'  => 3,
					'name' => 'Another Private Test Group',
					'text' => 'Another Private Test Group',
					'leaf' => true,
					'description' => 'Test Group',
					'username' => 'anotherprivatetestgroup',
					'session' => 'group:group_3',
					'token' => 'group:3',
					'type' => 'group',
					'email' => 'anothergrp+private@example.com',
					'privacy' => 'private',
					'image' => '/img/groups/default_small.png',
					'role' => 'group.manager',
					'members' => 4,
					'projects' => 0,
				),
			),
		);
		$this->assertEqual($nodes, $expected);
	}

	function testUserInvalidLimit()
	{
		$limit = 'invalid';

		$this->Groups->params = Router::parse('groups/user.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->params['form']['limit'] = $limit;
		$this->Groups->user();

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}	

	function testUserInvalidStart()
	{
		$start = 'invalid';

		$this->Groups->params = Router::parse('groups/user.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->params['form']['start'] = $start;
		$this->Groups->user();

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testMembers()
	{
		$group_id = 1;

		$this->Groups->params = Router::parse('groups/members/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->members($group_id);

		$this->assertTrue(isset($this->Groups->viewVars['response']));
		$response = $this->Groups->viewVars['response'];
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
					'group_id' => $group_id,
					'project_id' => 0,
					'role_id' => 2,
					'role' => 'group.member',
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
					'group_id' => $group_id,
					'project_id' => 0,
					'role_id' => 1,
					'role' => 'group.manager',
					'image' => '/img/users/default_small.png',
				),
			),
		);
		$this->assertEqual($response, $expected);
	}

	function testMembersNullGroupId()
	{
		$group_id = null;

		$this->Groups->params = Router::parse('groups/members/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->members($group_id);

		$this->assertEqual($this->Groups->error, 'missing_field');
	}

	function testMembersInvalidGroupId()
	{
		$group_id = 'invalid';

		$this->Groups->params = Router::parse('groups/members/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->members($group_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}	

	function testMembersInvalidGroupIdNotFound()
	{
		$group_id = 9000;

		$this->Groups->params = Router::parse('groups/members/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->members($group_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testMembersAccessDenied()
	{
		$group_id = 2;

		$this->Groups->params = Router::parse('groups/members/' . $group_id . '.json');
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->RequestHandler = new GroupsControllerMockRequestHandlerComponent();
		$this->Groups->RequestHandler->setReturnValue('prefers', true);

		$this->Groups->members($group_id);

		$this->assertEqual($this->Groups->error, 'access_denied');
	}

	function testRequest()
	{
		$group_id = 2;

		$this->Groups->Session->write('Auth.User', array(
			'id' => 3,
			'username' => 'thirduser',
			'name' => 'Third User',
			'changepass' => 0,
			'email' => 'thirduser@example.com',
		));

		$this->Groups->params = Router::parse('groups/request/' . $group_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		/*
		 * We can't mock up the MessagingComponent because
		 * it relies on several other components that
		 * wouldn't be loaded. Instead, we extend the
		 * messaging component with our custom messaging
		 * component, and replace it here.
		 */
		//$this->Groups->Messaging = $this->Groups->GroupsControllerTestMessaging;

		$this->Groups->request($group_id);

		$conditions = array(
			'Inbox.sender_id' => $this->Groups->Session->read('Auth.User.id'),
			'Inbox.template' => 'group_request',
			'Inbox.template_data LIKE' => '%"group_id":"' . $group_id .'"%',
			'Inbox.type' => 'received',
		);
		$this->Groups->Inbox->recursive = -1;
		$result = $this->Groups->Inbox->find('first', array('conditions' => $conditions));
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
				'sender_id'  => $this->Groups->Session->read('Auth.User.id'),
				'receiver_id'  => $result['Inbox']['receiver_id'],
				'receiver_type' => 'user',
				'message_id'  => $result['Inbox']['message_id'],
				'template'  => 'group_request',
				'template_data'  => '{"sender":"Third User","sender_id":"3","group":"Public Test Group","group_id":"2"}',
				'status' => 'unread',
				'trash'  => 0,
				'type' => 'received',
				'email'  => NULL,
				'parent_id'  => NULL
			),
		);
		$this->assertEqual($result, $expected);

		$this->assertEqual($this->Groups->redirectUrl, '/groups/profile/' . $group_id);
	}

	function testRequestNullGroupId()
	{
		$group_id = null;

		$this->Groups->params = Router::parse('groups/request/' . $group_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->request($group_id);

		$this->assertEqual($this->Groups->error, 'missing_field');
	}

	function testRequestInvalidGroupId()
	{
		$group_id = 'invalid';

		$this->Groups->params = Router::parse('groups/request/' . $group_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->request($group_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testRequestInvalidGroupIdNotFound()
	{
		$group_id = 9000;

		$this->Groups->params = Router::parse('groups/request/' . $group_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->request($group_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testRequestInvalidRole()
	{
		$group_id = 1;

		$this->Groups->params = Router::parse('groups/request/' . $group_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->request($group_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function testRequestInvalidRequest()
	{
		$group_id = 1;

		$this->Groups->Session->write('Auth.User', array(
			'id' => 3,
			'username' => 'thirduser',
			'changepass' => 0,
			'email' => 'thirduser@example.com',
		));

		$this->Groups->params = Router::parse('groups/request/' . $group_id);
		$this->Groups->beforeFilter();
		$this->Groups->Component->startup($this->Groups);

		$this->Groups->request($group_id);

		$this->assertEqual($this->Groups->error, 'invalid_field');
	}

	function endTest() {
		unset($this->Groups);
		ClassRegistry::flush();	
	}
}
?>
