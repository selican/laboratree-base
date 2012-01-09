<?php 
/* SVN FILE: $Id: user.test.php 2215 2011-09-21 17:30:32Z ethomason $ */
/* User Test cases generated on: 2010-12-20 15:00:58 : 1292857258*/
App::import('Model', 'User');
App::import('vendor', 'ZendLucene', array('file' => 'Zend' . DS . 'Search' . DS . 'Lucene.php'));

if(!class_exists('Zend_Search_Lucene'))
{
	Mock::generate('Zend_Search_Lucene');
}

class UserTestCase extends CakeTestCase {
	var $User = null;
	var $fixtures = array('app.helps', 'app.app_category', 'app.app_data', 'app.application', 'app.app_module', 'app.attachment', 'app.discussion', 'app.doc', 'app.docs_permission', 'app.docs_tag', 'app.docs_type_data', 'app.docs_type_field', 'app.docs_type', 'app.docs_type_row', 'app.docs_version', 'app.group', 'app.groups_address', 'app.groups_association', 'app.groups_award', 'app.groups_interest', 'app.groups_phone', 'app.groups_projects', 'app.groups_publication', 'app.groups_setting', 'app.groups_url', 'app.groups_users', 'app.inbox', 'app.inbox_hash', 'app.interest', 'app.message_archive', 'app.message', 'app.note', 'app.ontology_concept', 'app.preference', 'app.project', 'app.projects_association', 'app.projects_interest', 'app.projects_setting', 'app.projects_url', 'app.projects_users', 'app.role', 'app.setting', 'app.site_role', 'app.tag', 'app.type', 'app.url', 'app.user', 'app.users_address', 'app.users_association', 'app.users_award', 'app.users_education', 'app.users_interest', 'app.users_job', 'app.users_phone', 'app.users_preference', 'app.users_publication', 'app.users_url');

	function startTest() {
		$this->User =& ClassRegistry::init('User');
	}

	function testUserInstance() {
		$this->assertTrue(is_a($this->User, 'User'));
	}

	function testUserFind() {
		$this->User->recursive = -1;
		$results = $this->User->find('first');
		$this->assertTrue(!empty($results));

		$expected = array(
			'User' => array(
				'id'  => $results['User']['id'],
				'username'  => 'testuser',
				'password'  => $results['User']['password'],
				'email'  => 'testuser@example.com',
				'prefix'  => 'Mr.',
				'first_name'  => 'Test',
				'last_name'  => 'User',
				'name'  => 'Test User',
				'suffix'  => 'Esq.',
				'title'  => 'Programmer',
				'description'  => 'test',
				'status'  => 'Test',
				'gender' => 'male',
				'age'  => 50,
				'picture'  => null,
				'privacy' => 'private',
				'activity'  => $results['User']['activity'],
				'registered'  => $results['User']['registered'],
				'hash'  => $results['User']['hash'],
				'private_hash'  => $results['User']['private_hash'],
				'auth_token'  => 'AAAAA',
				'auth_timestamp'  => 1269625040,
				'confirmed'  => 1,
				'changepass'  => 0,
				'security_question'  => 1,
				'security_answer'  => $results['User']['security_answer'],
				'language_id'  => 1,
				'timezone_id'  => 1,
				'ip'  => '127.0.0.1',
				'admin'  => 0,
				'type' => 'user',
				'vivo'  => null 
			),
		);
		$this->assertEqual($results, $expected);
	}

	function testGet()
	{
		$user_id = 1;

		$results = $this->User->get($user_id);

		$expected = array(
			'User' => array(
				'id'  => $results['User']['id'],
				'username'  => 'testuser',
				'password'  => $results['User']['password'],
				'email'  => 'testuser@example.com',
				'prefix'  => 'Mr.',
				'first_name'  => 'Test',
				'last_name'  => 'User',
				'name'  => 'Test User',
				'suffix'  => 'Esq.',
				'title'  => 'Programmer',
				'description'  => 'test',
				'status'  => 'Test',
				'gender' => 'male',
				'age'  => 50,
				'picture'  => null,
				'privacy' => 'private',
				'activity'  => $results['User']['activity'],
				'registered'  => $results['User']['registered'],
				'hash'  => $results['User']['hash'],
				'private_hash'  => $results['User']['private_hash'],
				'auth_token'  => 'AAAAA',
				'auth_timestamp'  => 1269625040,
				'confirmed'  => 1,
				'changepass'  => 0,
				'security_question'  => 1,
				'security_answer'  => $results['User']['security_answer'],
				'language_id'  => 1,
				'timezone_id'  => 1,
				'ip'  => '127.0.0.1',
				'admin'  => 0,
				'type' => 'user',
				'vivo'  => null 
			),

		);

		$this->assertEqual($results, $expected);
	}

	function testGetNullUserId()
	{
		$user_id = null;

		try
		{
			$node = $this->User->get($user_id);
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testGetInvalidUserId()
	{
		$user_id = 'invalid';

		try
		{
			$node = $this->User->get($user_id);
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}
	
	function testActivity()
	{
		$user_id = 1;

		$result = $this->User->activity($user_id);

		$this->assertFalse($result === false);
	}

	function testActivityNullUserId()
	{
		$user_id = null;

		try
		{
			$node = $this->User->activity($user_id);
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testActivityInvalidUserId()
	{
		$user_id = 'invalid';

		try
		{
			$node = $this->User->activity($user_id);
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testToNode()
	{
		$this->User->recursive = -1;
		$results = $this->User->find('first');
		$node = $this->User->toNode($results);

		$expected = array(
			'id' => $node['id'],
			'name' => 'Test User',
			'username' => 'testuser',
			'session' => 'user:testuser',
			'token' => 'user:' . $node['id'],
			'type' => 'user',
			'activity' => $node['activity'],
			'group_id' => 0,
			'project_id' => 0,
			'role_id' => 0,
			'role' => 'Unknown',
			'image' => '/img/users/default_small.png',
		);

		$this->assertEqual($node, $expected);
	}

	function testToNodeNull() {
		try
		{
			$node = $this->User->toNode(null);
			$this->fail('InvalidArgumentException was expected');
		}
		catch (InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testToNodeNotArray() {
		try
		{
			$node = $this->User->toNode('string');
			$this->fail('InvalidArgumentException was expected');
		}
		catch (InvalidArgumentException $e)
		{
			$this->pass();
		}	
	}

	function testToNodeMissingModel() {
		try
		{
			$node = $this->User->toNode(array('id' => 1));
			$this->fail('InvalidArgumentException was expected');
		}
		catch (InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testToNodeMissingKey() {
		try
		{
			$node = $this->User->toNode(array('User' => array('test' => 1)));
			$this->fail('InvalidArgumentException was expected');
		}
		catch (InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testColleagues()
	{
		$user_id = 1;

		$colleagues = $this->User->colleagues($user_id);

		$expected = array(
			array(
				'ProjectsUsers' => array(
					'id' => 2,
					'project_id' => 1,
					'user_id' => 2,
					'role_id' => 6,
					'newrole_id' => 9,
				),
				'User' => array(
					'id' => $colleagues[0]['User']['id'],
					'username' => 'anotheruser',
					'password' => $colleagues[0]['User']['password'],
					'email' => 'anotheruser@example.com',
					'prefix' => 'Miss',
					'first_name' => 'Another',
					'last_name' => 'User',
					'name' => 'Another User',
					'suffix' => 'Esq.',
					'title' => 'Programmer',
					'description' => 'test',
					'status' => 'Test',
					'gender' => 'female',
					'age' => 40,
					'picture' => null,
					'privacy' => 'public',
					'activity' => $colleagues[0]['User']['activity'],
					'registered' => $colleagues[0]['User']['registered'],
					'hash' => $colleagues[0]['User']['hash'],
					'private_hash' => $colleagues[0]['User']['private_hash'],
					'auth_token' => 'AAAAA',
					'auth_timestamp' => 1269625040,
					'confirmed' => 1,
					'changepass' => 0,
					'security_question' => 1,
					'security_answer' => $colleagues[0]['User']['security_answer'],
					'language_id' => 1,
					'timezone_id' => 1,
					'ip' => '127.0.0.1',
					'admin' => 0,
					'type' => 'user',
					'vivo' => null,
				),
			),
			array(
				'ProjectsUsers' => array(
					'id' => 5,
					'project_id' => 3,
					'user_id' => 4,
					'role_id' => 6,
					'newrole_id' => 0,
				),
				'User' => array(
					'id' => $colleagues[1]['User']['id'],
					'username' => 'fourthuser',
					'password' => $colleagues[1]['User']['password'],
					'email' => 'fourthuser@example.com',
					'prefix' => 'Duke',
					'first_name' => 'Fourth',
					'last_name' => 'User',
					'name' => 'Fourth User',
					'suffix' => 'Esq.',
					'title' => 'Programmer',
					'description' => 'test',
					'status' => 'Test',
					'gender' => 'male',
					'age' => 72,
					'picture' => null,
					'privacy' => 'public',
					'activity' => $colleagues[1]['User']['activity'],
					'registered' => $colleagues[1]['User']['registered'],
					'hash' => $colleagues[1]['User']['hash'],
					'private_hash' => $colleagues[1]['User']['private_hash'],
					'auth_token' => 'AAAAA',
					'auth_timestamp' => 1269625040,
					'confirmed' => 1,
					'changepass' => 0,
					'security_question' => 1,
					'security_answer' => $colleagues[1]['User']['security_answer'],
					'language_id' => 1,
					'timezone_id' => 1,
					'ip' => '127.0.0.1',
					'admin' => 0,
					'type' => 'user',
					'vivo' => null,
				),
			),
			array(
				'ProjectsUsers' => array(
					'id' => 4,
					'project_id' => 3,
					'user_id' => 3,
					'role_id' => 5,
					'newrole_id' => 0,
				),
				'User' => array(
					'id' => $colleagues[2]['User']['id'],
					'username' => 'thirduser',
					'password' => $colleagues[2]['User']['password'],
					'email' => 'thirduser@example.com',
					'prefix' => 'Duke',
					'first_name' => 'Third',
					'last_name' => 'User',
					'name' => 'Third User',
					'suffix' => 'Esq.',
					'title' => 'Programmer',
					'description' => 'test',
					'status' => 'Test',
					'gender' => 'male',
					'age' => 57,
					'picture' => null,
					'privacy' => 'public',
					'activity' => $colleagues[2]['User']['activity'],
					'registered' => $colleagues[2]['User']['registered'],
					'hash' => $colleagues[2]['User']['hash'],
					'private_hash' => $colleagues[2]['User']['private_hash'],
					'auth_token' => 'AAAAA',
					'auth_timestamp' => 1269625040,
					'confirmed' => 1,
					'changepass' => 0,
					'security_question' => 1,
					'security_answer' => $colleagues[2]['User']['security_answer'],
					'language_id' => 1,
					'timezone_id' => 1,
					'ip' => '127.0.0.1',
					'admin' => 0,
					'type' => 'user',
					'vivo' => null,
				),
			),
		);

		$this->assertEqual($colleagues, $expected);
	}

	function testColleaguesWithQuery()
	{
		$user_id = 1;
		$query = 'Another';

		$colleagues = $this->User->colleagues($user_id, $query);

		$expected = array(
			array(
				'ProjectsUsers' => array(
					'id' => 2,
					'project_id' => 1,
					'user_id' => 2,
					'role_id' => 6,
					'newrole_id' => 9,
				),
				'User' => array(
					'id' => $colleagues[0]['User']['id'],
					'username' => 'anotheruser',
					'password' => $colleagues[0]['User']['password'],
					'email' => 'anotheruser@example.com',
					'prefix' => 'Miss',
					'first_name' => 'Another',
					'last_name' => 'User',
					'name' => 'Another User',
					'suffix' => 'Esq.',
					'title' => 'Programmer',
					'description' => 'test',
					'status' => 'Test',
					'gender' => 'female',
					'age' => 40,
					'picture' => null,
					'privacy' => 'public',
					'activity' => $colleagues[0]['User']['activity'],
					'registered' => $colleagues[0]['User']['registered'],
					'hash' => $colleagues[0]['User']['hash'],
					'private_hash' => $colleagues[0]['User']['private_hash'],
					'auth_token' => 'AAAAA',
					'auth_timestamp' => 1269625040,
					'confirmed' => 1,
					'changepass' => 0,
					'security_question' => 1,
					'security_answer' => $colleagues[0]['User']['security_answer'],
					'language_id' => 1,
					'timezone_id' => 1,
					'ip' => '127.0.0.1',
					'admin' => 0,
					'type' => 'user',
					'vivo' => null,
				),
			),
		);

		$this->assertEqual($colleagues, $expected);
	}

	function testColleaguesWithSince()
	{
		$user_id = 1;
		$query = null;
		$since = '2010-01-01 01:00:00';

		$colleagues = $this->User->colleagues($user_id, $query, $since);

		$expected = array(
			array(
				'ProjectsUsers' => array(
					'id' => 2,
					'project_id' => 1,
					'user_id' => 2,
					'role_id' => 6,
					'newrole_id' => 9,
				),
				'User' => array(
					'id' => $colleagues[0]['User']['id'],
					'username' => 'anotheruser',
					'password' => $colleagues[0]['User']['password'],
					'email' => 'anotheruser@example.com',
					'prefix' => 'Miss',
					'first_name' => 'Another',
					'last_name' => 'User',
					'name' => 'Another User',
					'suffix' => 'Esq.',
					'title' => 'Programmer',
					'description' => 'test',
					'status' => 'Test',
					'gender' => 'female',
					'age' => 40,
					'picture' => null,
					'privacy' => 'public',
					'activity' => $colleagues[0]['User']['activity'],
					'registered' => $colleagues[0]['User']['registered'],
					'hash' => $colleagues[0]['User']['hash'],
					'private_hash' => $colleagues[0]['User']['private_hash'],
					'auth_token' => 'AAAAA',
					'auth_timestamp' => 1269625040,
					'confirmed' => 1,
					'changepass' => 0,
					'security_question' => 1,
					'security_answer' => $colleagues[0]['User']['security_answer'],
					'language_id' => 1,
					'timezone_id' => 1,
					'ip' => '127.0.0.1',
					'admin' => 0,
					'type' => 'user',
					'vivo' => null,
				),
			),
			array(
				'ProjectsUsers' => array(
					'id' => 5,
					'project_id' => 3,
					'user_id' => 4,
					'role_id' => 6,
					'newrole_id' => 0,
				),
				'User' => array(
					'id' => $colleagues[1]['User']['id'],
					'username' => 'fourthuser',
					'password' => $colleagues[1]['User']['password'],
					'email' => 'fourthuser@example.com',
					'prefix' => 'Duke',
					'first_name' => 'Fourth',
					'last_name' => 'User',
					'name' => 'Fourth User',
					'suffix' => 'Esq.',
					'title' => 'Programmer',
					'description' => 'test',
					'status' => 'Test',
					'gender' => 'male',
					'age' => 72,
					'picture' => null,
					'privacy' => 'public',
					'activity' => $colleagues[1]['User']['activity'],
					'registered' => $colleagues[1]['User']['registered'],
					'hash' => $colleagues[1]['User']['hash'],
					'private_hash' => $colleagues[1]['User']['private_hash'],
					'auth_token' => 'AAAAA',
					'auth_timestamp' => 1269625040,
					'confirmed' => 1,
					'changepass' => 0,
					'security_question' => 1,
					'security_answer' => $colleagues[1]['User']['security_answer'],
					'language_id' => 1,
					'timezone_id' => 1,
					'ip' => '127.0.0.1',
					'admin' => 0,
					'type' => 'user',
					'vivo' => null,
				),
			),
			array(
				'ProjectsUsers' => array(
					'id' => 4,
					'project_id' => 3,
					'user_id' => 3,
					'role_id' => 5,
					'newrole_id' => 0,
				),
				'User' => array(
					'id' => $colleagues[2]['User']['id'],
					'username' => 'thirduser',
					'password' => $colleagues[2]['User']['password'],
					'email' => 'thirduser@example.com',
					'prefix' => 'Duke',
					'first_name' => 'Third',
					'last_name' => 'User',
					'name' => 'Third User',
					'suffix' => 'Esq.',
					'title' => 'Programmer',
					'description' => 'test',
					'status' => 'Test',
					'gender' => 'male',
					'age' => 57,
					'picture' => null,
					'privacy' => 'public',
					'activity' => $colleagues[2]['User']['activity'],
					'registered' => $colleagues[2]['User']['registered'],
					'hash' => $colleagues[2]['User']['hash'],
					'private_hash' => $colleagues[2]['User']['private_hash'],
					'auth_token' => 'AAAAA',
					'auth_timestamp' => 1269625040,
					'confirmed' => 1,
					'changepass' => 0,
					'security_question' => 1,
					'security_answer' => $colleagues[2]['User']['security_answer'],
					'language_id' => 1,
					'timezone_id' => 1,
					'ip' => '127.0.0.1',
					'admin' => 0,
					'type' => 'user',
					'vivo' => null,
				),
			),
		);

		$this->assertEqual($colleagues, $expected);
	}

	function testColleaguesNullUserId()
	{
		$user_id = null;
		try
		{
			$colleagues = $this->User->colleagues($user_id);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testColleaguesInvalidUserId()
	{
		$user_id = 'invalid';
		try
		{
			$colleagues = $this->User->colleagues($user_id);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testColleaguesNoResultsQuery()
	{
		$user_id = 1;
		$query = 'NORESULTS';

		$colleagues = $this->User->colleagues($user_id, $query);
		$this->assertTrue(empty($colleagues));
	}

	function testColleaguesNoResultsSince()
	{
		$user_id = 1;
		$query = null;
		$since = date('Y-m-d H:i:s', strtotime('now +10 years'));

		$colleagues = $this->User->colleagues($user_id, $query, $since);
		$this->assertTrue(empty($colleagues));
	}

	function testColleaguesInvalidSince()
	{
		$user_id = 1;
		$query = null;
		$since = 'invalid';

		$colleagues = $this->User->colleagues($user_id, $query, $since);
		$this->assertTrue(empty($colleagues));
	}

	function testContacts()
	{
		$user_id = 1;

		$contacts = $this->User->contacts($user_id);

		$expected = array(
			array(
				'User' => array(
					'id' => $contacts[0]['User']['id'],
					'username' => 'anotheruser',
					'password' => $contacts[0]['User']['password'],
					'email' => 'anotheruser@example.com',
					'prefix' => 'Miss',
					'first_name' => 'Another',
					'last_name' => 'User',
					'name' => 'Another User',
					'suffix' => 'Esq.',
					'title' => 'Programmer',
					'description' => 'test',
					'status' => 'Test',
					'gender' => 'female',
					'age' => 40,
					'picture' => null,
					'privacy' => 'public',
					'activity' => $contacts[0]['User']['activity'],
					'registered' => $contacts[0]['User']['registered'],
					'hash' => $contacts[0]['User']['hash'],
					'private_hash' => $contacts[0]['User']['private_hash'],
					'auth_token' => 'AAAAA',
					'auth_timestamp' => 1269625040,
					'confirmed' => 1,
					'changepass' => 0,
					'security_question' => 1,
					'security_answer' => $contacts[0]['User']['security_answer'],
					'language_id' => 1,
					'timezone_id' => 1,
					'ip' => '127.0.0.1',
					'admin' => 0,
					'type' => 'user',
					'vivo' => null,
				),
			),
			array(
				'User' => array(
					'id' => $contacts[1]['User']['id'],
					'username' => 'fifthuser',
					'password' => $contacts[1]['User']['password'],
					'email' => 'fifthuser@example.com',
					'prefix' => 'Lady',
					'first_name' => 'Fifth',
					'last_name' => 'User',
					'name' => 'Fifth User',
					'suffix' => null,
					'title' => 'Programmer',
					'description' => 'test',
					'status' => 'Test',
					'gender' => 'female',
					'age' => 21,
					'picture' => null,
					'privacy' => 'private',
					'activity' => $contacts[1]['User']['activity'],
					'registered' => $contacts[1]['User']['registered'],
					'hash' => $contacts[1]['User']['hash'],
					'private_hash' => $contacts[1]['User']['private_hash'],
					'auth_token' => 'AAAAA',
					'auth_timestamp' => 1269625040,
					'confirmed' => 1,
					'changepass' => 0,
					'security_question' => 1,
					'security_answer' => $contacts[1]['User']['security_answer'],
					'language_id' => 1,
					'timezone_id' => 1,
					'ip' => '127.0.0.1',
					'admin' => 0,
					'type' => 'user',
					'vivo' => null,
				),
			),
			array(
				'User' => array(
					'id' => $contacts[2]['User']['id'],
					'username' => 'fourthuser',
					'password' => $contacts[2]['User']['password'],
					'email' => 'fourthuser@example.com',
					'prefix' => 'Duke',
					'first_name' => 'Fourth',
					'last_name' => 'User',
					'name' => 'Fourth User',
					'suffix' => 'Esq.',
					'title' => 'Programmer',
					'description' => 'test',
					'status' => 'Test',
					'gender' => 'male',
					'age' => 72,
					'picture' => null,
					'privacy' => 'public',
					'activity' => $contacts[2]['User']['activity'],
					'registered' => $contacts[2]['User']['registered'],
					'hash' => $contacts[2]['User']['hash'],
					'private_hash' => $contacts[2]['User']['private_hash'],
					'auth_token' => 'AAAAA',
					'auth_timestamp' => 1269625040,
					'confirmed' => 1,
					'changepass' => 0,
					'security_question' => 1,
					'security_answer' => $contacts[2]['User']['security_answer'],
					'language_id' => 1,
					'timezone_id' => 1,
					'ip' => '127.0.0.1',
					'admin' => 0,
					'type' => 'user',
					'vivo' => null,
				),
			),
			array(
				'User' => array(
					'id' => $contacts[3]['User']['id'],
					'username' => 'sixthuser',
					'password' => $contacts[3]['User']['password'],
					'email' => 'sixthuser@example.com',
					'prefix' => null,
					'first_name' => null,
					'last_name' => null,
					'name' => 'Sixth User',
					'suffix' => null,
					'title' => null,
					'description' => null,
					'status' => null,
					'gender' => 'unknown',
					'age' => null, 
					'picture' => null,
					'privacy' => 'private',
					'activity' => $contacts[3]['User']['activity'],
					'registered' => $contacts[3]['User']['registered'],
					'hash' => $contacts[3]['User']['hash'],
					'private_hash' => $contacts[3]['User']['private_hash'],
					'auth_token' => null,
					'auth_timestamp' => 0,
					'confirmed' => 0,
					'changepass' => 0,
					'security_question' => 0,
					'security_answer' => null,
					'language_id' => 1,
					'timezone_id' => 1,
					'ip' => '127.0.0.1',
					'admin' => 0,
					'type' => 'user',
					'vivo' => null,
				),
			),
			array(
				'User' => array(
					'id' => $contacts[4]['User']['id'],
					'username' => 'thirduser',
					'password' => $contacts[4]['User']['password'],
					'email' => 'thirduser@example.com',
					'prefix' => 'Duke',
					'first_name' => 'Third',
					'last_name' => 'User',
					'name' => 'Third User',
					'suffix' => 'Esq.',
					'title' => 'Programmer',
					'description' => 'test',
					'status' => 'Test',
					'gender' => 'male',
					'age' => 57,
					'picture' => null,
					'privacy' => 'public',
					'activity' => $contacts[4]['User']['activity'],
					'registered' => $contacts[4]['User']['registered'],
					'hash' => $contacts[4]['User']['hash'],
					'private_hash' => $contacts[4]['User']['private_hash'],
					'auth_token' => 'AAAAA',
					'auth_timestamp' => 1269625040,
					'confirmed' => 1,
					'changepass' => 0,
					'security_question' => 1,
					'security_answer' => $contacts[4]['User']['security_answer'],
					'language_id' => 1,
					'timezone_id' => 1,
					'ip' => '127.0.0.1',
					'admin' => 0,
					'type' => 'user',
					'vivo' => null,
				),
			),
		);

		$this->assertEqual($contacts, $expected);
	}

	function testContactsWithQuery()
	{
		$user_id = 1;
		$query = 'Another';

		$contacts = $this->User->contacts($user_id, $query);

		$expected = array(
			array(
				'User' => array(
					'id' => $contacts[0]['User']['id'],
					'username' => 'anotheruser',
					'password' => $contacts[0]['User']['password'],
					'email' => 'anotheruser@example.com',
					'prefix' => 'Miss',
					'first_name' => 'Another',
					'last_name' => 'User',
					'name' => 'Another User',
					'suffix' => 'Esq.',
					'title' => 'Programmer',
					'description' => 'test',
					'status' => 'Test',
					'gender' => 'female',
					'age' => 40,
					'picture' => null,
					'privacy' => 'public',
					'activity' => $contacts[0]['User']['activity'],
					'registered' => $contacts[0]['User']['registered'],
					'hash' => $contacts[0]['User']['hash'],
					'private_hash' => $contacts[0]['User']['private_hash'],
					'auth_token' => 'AAAAA',
					'auth_timestamp' => 1269625040,
					'confirmed' => 1,
					'changepass' => 0,
					'security_question' => 1,
					'security_answer' => $contacts[0]['User']['security_answer'],
					'language_id' => 1,
					'timezone_id' => 1,
					'ip' => '127.0.0.1',
					'admin' => 0,
					'type' => 'user',
					'vivo' => null,
				),
			),
		);

		$this->assertEqual($contacts, $expected);
	}

	function testContactsNoResultsQuery()
	{
		$user_id = 1;
		$query = 'NORESULTS';

		$contacts = $this->User->contacts($user_id, $query);
		$this->assertTrue(empty($contacts));
	}
}
?>
