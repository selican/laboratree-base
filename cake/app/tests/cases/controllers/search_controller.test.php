<?php
App::import('Controller','Search');
App::import('Component', 'RequestHandler');
App::import('Component', 'Lucene');

Mock::generatePartial('RequestHandlerComponent', 'SearchControllerMockRequestHandlerComponent', array('prefers'));
Mock::generatePartial('LuceneComponent', 'SearchControllerMockLuceneComponent', array('query'));

class SearchControllerTestSearchController extends SearchController {
	var $name = 'Search';
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

class SearchControllerTest extends CakeTestCase {
	var $Search = null;
	var $fixtures = array('app.helps', 'app.app_category', 'app.app_data', 'app.application', 'app.app_module', 'app.attachment', 'app.digest', 'app.discussion', 'app.doc', 'app.docs_permission', 'app.docs_tag', 'app.docs_type_data', 'app.docs_type_field', 'app.docs_type', 'app.docs_type_row', 'app.docs_version', 'app.group', 'app.groups_address', 'app.groups_association', 'app.groups_award', 'app.groups_interest', 'app.groups_phone', 'app.groups_projects', 'app.groups_publication', 'app.groups_setting', 'app.groups_url', 'app.groups_users', 'app.inbox', 'app.inbox_hash', 'app.interest', 'app.message_archive', 'app.message', 'app.note', 'app.ontology_concept', 'app.preference', 'app.project', 'app.projects_association', 'app.projects_interest', 'app.projects_setting', 'app.projects_url', 'app.projects_users', 'app.role', 'app.setting', 'app.site_role', 'app.tag', 'app.type', 'app.url', 'app.user', 'app.users_address', 'app.users_association', 'app.users_award', 'app.users_education', 'app.users_interest', 'app.users_job', 'app.users_phone', 'app.users_preference', 'app.users_publication', 'app.users_url', 'app.ldap_user');
	
	function startTest() {
		$this->Search = new SearchControllerTestSearchController();
		$this->Search->constructClasses();
		$this->Search->Component->initialize($this->Search);
		
		$this->Search->Session->write('Auth.User', array(
			'id' => 1,
			'username' => 'testuser',
			'changepass' => 0,
		));
	}
	
	function testSearchControllerInstance() {
		$this->assertTrue(is_a($this->Search, 'SearchController'));
	}

	function testIndex()
	{
		$query = 'test';

		$this->Search->params = Router::parse('search/index/' . $query . '.json');
		$this->Search->beforeFilter();
		$this->Search->Component->startup($this->Search);

		$this->Search->RequestHandler = new SearchControllerMockRequestHandlerComponent();
		$this->Search->RequestHandler->setReturnValue('prefers', false);

		// TODO: Consider adding access denied entries for each model
		$search = array(
			array(
				'model' => 'Discussion',
				'score' => 1,
				'Discussion_id' => 2,
				'Discussion_table_type' => 'group',
				'Discussion_table_id' => 1,
				'Discussion_title' => 'Test Category',
				'Discussion_content' => 'Test Category Description',
				'Discussion_created' => '2010-12-20 14:54:29',
			),
			array(
				'model' => 'Doc',
				'score' => 1,
				'Doc_id' => 3,
				'Doc_table_id' => 1,
				'Doc_table_type' => 'user',
				'Doc_title' => 'Test User Private Document',
				'Doc_description' => 'Test',
				'Doc_created' => '2010-12-20 14:54:37',
			),
			array(
				'model' => 'Group',
				'score' => 1,
				'Group_id' => 1,
				'Group_name' => 'Private Test Group',
				'Group_description' => 'Test Group',
				'Group_created' => '2010-12-20 14:56:15',
			),
			array(
				'model' => 'Inbox',
				'score' => 1,
				'Inbox_id' => 1,
				'Inbox_sender_id' => 1,
				'Inbox_receiver_type' => 'user',
				'Inbox_receiver_id' => 1,
				'Message_subject' => 'Test',
				'Message_body' => 'Test',
				'Message_date' => '2010-12-20 14:58:15',
			),
			array(
				'model' => 'Project',
				'score' => 1,
				'Project_id' => 1,
				'Project_name' => 'Private Test Project',
				'Project_description' => 'Private Test Project',
				'Project_created' => '2010-12-20 14:59:06',
			),
			// This entry verifies that our access checks are working
			array(
				'model' => 'Project',
				'score' => 1,
				'Project_id' => 3,
				'Project_name' => 'Private Third Project',
				'Project_description' => 'Private Third Project',
				'Project_created' => '2010-12-20 14:59:06',
			),
			array(
				'model' => 'User',
				'score' => 1,
				'User_id' => 1,
				'User_name' => 'Test User',
				'User_description' => 'test',
				'User_registered' => '2010-12-20 15:00:58',
			),
			array(
				'model' => 'Note',
				'score' => 1,
				'Note_id' => 1,
				'Note_table_type' => 'user',
				'Note_table_id' => 1,
				'Note_title' => 'Home',
				'Note_content' => 'Welcome Home',
				'Note_created' => '2010-12-20 14:58:44',
			),
		);

		for($i = 0; $i < count($search); $i++)
		{
			$search[$i] = (object) $search[$i];
		}

		$this->Search->Lucene = new SearchControllerMockLuceneComponent();
		$this->Search->Lucene->setReturnValue('query', $search);

		$this->Search->index($query);

		$this->assertTrue(isset($this->Search->viewVars['response']));
		$response = $this->Search->viewVars['response'];
		$this->assertTrue($response['success']);

		$expected = array(
			'success' => true,
			'results' => array(
				'Discussions' => array(
					array(
						'id' => $response['results']['Discussions'][0]['id'],
						'uniqId' => 'Discussion_' . $response['results']['Discussions'][0]['id'],
						'model' => 'Discussion',
						'score' => 1,
						'view' => '/discussions/view/' . $response['results']['Discussions'][0]['id'],
						'date' => $response['results']['Discussions'][0]['date'],
						'title' => 'Test Category',
						'description' => 'Test Category Description',
					),
				),
				'Documents' => array(
					array(
						'id' => $response['results']['Documents'][0]['id'],
						'uniqId' => 'Doc_' . $response['results']['Documents'][0]['id'],
						'model' => 'Doc',
						'score' => 1,
						'view' => '/docs/view/' . $response['results']['Documents'][0]['id'],
						'date' => $response['results']['Documents'][0]['date'],
						'title' => 'Test User Private Document',
						'description' => 'Test',
					),
				),
				'Groups' => array(
					array(
						'id' => $response['results']['Groups'][0]['id'],
						'uniqId' => 'Group_' . $response['results']['Groups'][0]['id'],
						'model' => 'Group',
						'score' => 1,
						'view' => '/groups/profile/' . $response['results']['Groups'][0]['id'],
						'date' => $response['results']['Groups'][0]['date'],
						'title' => 'Private Test Group',
						'description' => 'Test Group',
					),
				),
				'Inbox Messages' => array(
					array(
						'id' => $response['results']['Inbox Messages'][0]['id'],
						'uniqId' => 'Inbox_' . $response['results']['Inbox Messages'][0]['id'],
						'model' => 'Inbox',
						'score' => 1,
						'view' => '/inbox/view/' . $response['results']['Inbox Messages'][0]['id'],
						'date' => $response['results']['Inbox Messages'][0]['date'],
						'title' => 'Test',
						'description' => 'Test',
					),
				),
				'Projects' => array(
					array(
						'id' => $response['results']['Projects'][0]['id'],
						'uniqId' => 'Project_' . $response['results']['Projects'][0]['id'],
						'model' => 'Project',
						'score' => 1,
						'view' => '/projects/profile/' . $response['results']['Projects'][0]['id'],
						'date' => $response['results']['Projects'][0]['date'],
						'title' => 'Private Test Project',
						'description' => 'Private Test Project',
					),
					array(
						'id' => $response['results']['Projects'][1]['id'],
						'uniqId' => 'Project_' . $response['results']['Projects'][1]['id'],
						'model' => 'Project',
						'score' => 1,
						'view' => '/projects/profile/' . $response['results']['Projects'][1]['id'],
						'date' => $response['results']['Projects'][1]['date'],
						'title' => 'Private Third Project',
						'description' => 'Private Third Project',
					),
				),
				'Users' => array(
					array(
						'id' => $response['results']['Users'][0]['id'],
						'uniqId' => 'User_' . $response['results']['Users'][0]['id'],
						'model' => 'User',
						'score' => 1,
						'view' => '/users/profile/' . $response['results']['Users'][0]['id'],
						'date' => $response['results']['Users'][0]['date'],
						'title' => 'Test User',
						'description' => 'test',
					),
				),
				'Notes' => array(
					array(
						'id' => $response['results']['Notes'][0]['id'],
						'uniqId' => 'Note_' . $response['results']['Notes'][0]['id'],
						'model' => 'Note',
						'score' => 1,
						'view' => '/notes/view/' . $response['results']['Notes'][0]['id'],
						'date' => $response['results']['Notes'][0]['date'],
						'title' => 'Home',
						'description' => 'Welcome Home',
					),
				),
			),
			'previous' => $response['previous'],
			'next' => $response['next'],
			'last' => $response['last'],
			'query' => $query,
			'total' => $response['total'],
			'start' => $response['start'],
			'limit' => $response['limit'],
		);
		$this->assertEqual($response, $expected);
	}

	function testIndexInvalidLimit()
	{
		$query = 'test';
		$limit = 'invalid';

		$this->Search->params = Router::parse('search/index/' . $query . '.json');
		$this->Search->beforeFilter();
		$this->Search->Component->startup($this->Search);

		$this->Search->RequestHandler = new SearchControllerMockRequestHandlerComponent();
		$this->Search->RequestHandler->setReturnValue('prefers', true);

		$this->Search->params['url']['limit'] = $limit;
		$this->Search->index($query);

		$this->assertEqual($this->Search->error, 'invalid_field');
	}

	function testIndexInvalidStart()
	{
		$query = 'test';
		$start = 'invalid';	

		$this->Search->params = Router::parse('search/index/' . $query . '.json');
		$this->Search->beforeFilter();
		$this->Search->Component->startup($this->Search);

		$this->Search->RequestHandler = new SearchControllerMockRequestHandlerComponent();
		$this->Search->RequestHandler->setReturnValue('prefers', true);

		$this->Search->params['url']['start'] = $start;
		$this->Search->index($query);

		$this->assertEqual($this->Search->error, 'invalid_field');
	}

	function testOpenSearchNotXml()
	{
		$this->Search->params = Router::parse('search/opensearch');
		$this->Search->beforeFilter();
		$this->Search->Component->startup($this->Search);

		$this->Search->RequestHandler = new SearchControllerMockRequestHandlerComponent();
		$this->Search->RequestHandler->setReturnValue('prefers', false);

		$this->Search->opensearch();
		$this->assertEqual($this->Search->error, 'error404');
	}

	function endTest() {
		unset($this->Search);
		ClassRegistry::flush();	
	}
}
?>
