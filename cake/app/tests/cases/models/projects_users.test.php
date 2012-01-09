<?php 
/* SVN FILE: $Id$ */
/* ProjectsUsers Test cases generated on: 2010-12-20 14:59:45 : 1292857185*/
App::import('Model', 'ProjectsUsers');

class ProjectsUsersTestCase extends CakeTestCase {
	var $ProjectsUsers = null;
	var $fixtures = array('app.helps', 'app.app_category', 'app.app_data', 'app.application', 'app.app_module', 'app.attachment', 'app.discussion', 'app.doc', 'app.docs_permission', 'app.docs_tag', 'app.docs_type_data', 'app.docs_type_field', 'app.docs_type', 'app.docs_type_row', 'app.docs_version', 'app.group', 'app.groups_address', 'app.groups_association', 'app.groups_award', 'app.groups_interest', 'app.groups_phone', 'app.groups_projects', 'app.groups_publication', 'app.groups_setting', 'app.groups_url', 'app.groups_users', 'app.inbox', 'app.inbox_hash', 'app.interest', 'app.message_archive', 'app.message', 'app.note', 'app.ontology_concept', 'app.preference', 'app.project', 'app.projects_association', 'app.projects_interest', 'app.projects_setting', 'app.projects_url', 'app.projects_users', 'app.role', 'app.setting', 'app.site_role', 'app.tag', 'app.type', 'app.url', 'app.user', 'app.users_address', 'app.users_association', 'app.users_award', 'app.users_education', 'app.users_interest', 'app.users_job', 'app.users_phone', 'app.users_preference', 'app.users_publication', 'app.users_url');

	function startTest() {
		$this->ProjectsUsers =& ClassRegistry::init('ProjectsUsers');
	}

	function testProjectsUsersInstance() {
		$this->assertTrue(is_a($this->ProjectsUsers, 'ProjectsUsers'));
	}

	function testProjectsUsersFind() {
		$this->ProjectsUsers->recursive = -1;
		$results = $this->ProjectsUsers->find('first');
		$this->assertTrue(!empty($results));

		$expected = array('ProjectsUsers' => array(
			'id'  => 1,
			'project_id'  => 1,
			'user_id'  => 1,
			'role_id'  => 5,
			'newrole_id'  => 8
		));
		$this->assertEqual($results, $expected);
	}

	function testProjects()
	{
		$user_id = 1;

		$results = $this->ProjectsUsers->projects($user_id);

		$expected = array(
			array(
				'ProjectsUsers' => array(
					'id' => $results[0]['ProjectsUsers']['id'],
					'project_id' => 3,
					'user_id' => $results[0]['ProjectsUsers']['user_id'],
					'role_id' => 5,
					'newrole_id' => 14,
				),
				'User' => array(
					'id' => $results[0]['User']['id'],
					'username' => 'testuser',
					'password' => $results[0]['User']['password'],
					'email' => 'testuser@example.com',
					'alt_email' => 'testtest@example.com',
					'prefix' => 'Mr.',
					'first_name' => 'Test',
					'last_name' => 'User',
					'name' => 'Test User',
					'suffix' => 'Esq.',
					'title' => 'Programmer',
					'description' => 'test',
					'status' => 'Test',
					'gender' => 'male',
					'age' => 50,
					'picture' => null,
					'privacy' => 'private',
					'activity' => $results[0]['User']['activity'],
					'registered' => $results[0]['User']['registered'],
					'hash' => $results[0]['User']['hash'],
					'private_hash' => $results[0]['User']['private_hash'],
					'auth_token' => 'AAAAA',
					'auth_timestamp' => 1269625040,
					'confirmed' => 1,
					'changepass' => 0,
					'security_question' => 1,
					'security_answer' => $results[0]['User']['security_answer'],
					'language_id' => 1,
					'timezone_id' => 1,
					'ip' => '127.0.0.1',
					'admin' => 0,
					'type' => 'user',
					'vivo' => null,
				),
				'Project' => array(
					'id' => $results[0]['Project']['id'],
					'name' => 'Another Private Test Project',
					'description' => 'Another Private Test Project',
					'privacy' => 'private',
					'picture' => null,
					'email' => 'anotherprj+private@example.com',
					'created' => $results[0]['Project']['created'],
					'User' => array(
						array(
							'id' => $results[0]['Project']['User'][0]['id'],
							'username' => 'anotheruser',
							'password' => $results[0]['Project']['User'][0]['password'],
							'email' => 'anotheruser@example.com',
							'alt_email' => 'anothertest@example.com',
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
							'activity' => $results[0]['Project']['User'][0]['activity'],
							'registered' => $results[0]['Project']['User'][0]['registered'],
							'hash' => $results[0]['Project']['User'][0]['hash'],
							'private_hash' => $results[0]['Project']['User'][0]['private_hash'],
							'auth_token' => 'AAAAA',
							'auth_timestamp' => 1269625040,
							'confirmed' => 1,
							'changepass' => 0,
							'security_question' => 1,
							'security_answer' => $results[0]['Project']['User'][0]['security_answer'],
							'language_id' => 1,
							'timezone_id' => 1,
							'ip' => '127.0.0.1',
							'admin' => 0,
							'type' => 'user',
							'vivo' => null,
							'ProjectsUsers' => array(
								'id' => $results[0]['Project']['User'][0]['ProjectsUsers']['id'],
								'project_id' => $results[0]['Project']['User'][0]['ProjectsUsers']['project_id'],
								'user_id' => $results[0]['Project']['User'][0]['id'],
								'role_id' => 5,
								'newrole_id' => 14,
							),
						),
						array(
							'id' => $results[0]['Project']['User'][1]['id'],
							'username' => 'fourthuser',
							'password' => $results[0]['Project']['User'][1]['password'],
							'email' => 'fourthuser@example.com',
							'alt_email' => 'fourthtest@example.com',
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
							'activity' => $results[0]['Project']['User'][1]['activity'],
							'registered' => $results[0]['Project']['User'][1]['registered'],
							'hash' => $results[0]['Project']['User'][1]['hash'],
							'private_hash' => $results[0]['Project']['User'][1]['private_hash'],
							'auth_token' => 'AAAAA',
							'auth_timestamp' => 1269625040,
							'confirmed' => 1,
							'changepass' => 0,
							'security_question' => 1,
							'security_answer' => $results[0]['Project']['User'][1]['security_answer'],
							'language_id' => 1,
							'timezone_id' => 1,
							'ip' => '127.0.0.1',
							'admin' => 0,
							'type' => 'user',
							'vivo' => null,
							'ProjectsUsers' => array(
								'id' => $results[0]['Project']['User'][1]['ProjectsUsers']['id'],
								'project_id' => $results[0]['Project']['User'][1]['ProjectsUsers']['project_id'],
								'user_id' => $results[0]['Project']['User'][1]['id'],
								'role_id' => 8,
								'newrole_id' => 0,
							),
						),
						array(
							'id' => $results[0]['Project']['User'][2]['id'],
							'username' => 'testuser',
							'password' => $results[0]['Project']['User'][2]['password'],
							'email' => 'testuser@example.com',
							'alt_email' => 'testtest@example.com',
							'prefix' => 'Mr.',
							'first_name' => 'Test',
							'last_name' => 'User',
							'name' => 'Test User',
							'suffix' => 'Esq.',
							'title' => 'Programmer',
							'description' => 'test',
							'status' => 'Test',
							'gender' => 'male',
							'age' => 50,
							'picture' => null,
							'privacy' => 'private',
							'activity' => $results[0]['Project']['User'][2]['activity'],
							'registered' => $results[0]['Project']['User'][2]['registered'],
							'hash' => $results[0]['Project']['User'][2]['hash'],
							'private_hash' => $results[0]['Project']['User'][2]['private_hash'],
							'auth_token' => 'AAAAA',
							'auth_timestamp' => 1269625040,
							'confirmed' => 1,
							'changepass' => 0,
							'security_question' => 1,
							'security_answer' => $results[0]['Project']['User'][2]['security_answer'],
							'language_id' => 1,
							'timezone_id' => 1,
							'ip' => '127.0.0.1',
							'admin' => 0,
							'type' => 'user',
							'vivo' => null,
							'ProjectsUsers' => array(
								'id' => $results[0]['Project']['User'][2]['ProjectsUsers']['id'],
								'project_id' => $results[0]['Project']['User'][2]['ProjectsUsers']['project_id'],
								'user_id' => $results[0]['Project']['User'][2]['id'],
								'role_id' => 5,
								'newrole_id' => 14,
							),
						),
						array(
							'id' => $results[0]['Project']['User'][3]['id'],
							'username' => 'thirduser',
							'password' => $results[0]['Project']['User'][3]['password'],
							'email' => 'thirduser@example.com',
							'alt_email' => 'thirdtest@example.com',
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
							'activity' => $results[0]['Project']['User'][3]['activity'],
							'registered' => $results[0]['Project']['User'][3]['registered'],
							'hash' => $results[0]['Project']['User'][3]['hash'],
							'private_hash' => $results[0]['Project']['User'][3]['private_hash'],
							'auth_token' => 'AAAAA',
							'auth_timestamp' => 1269625040,
							'confirmed' => 1,
							'changepass' => 0,
							'security_question' => 1,
							'security_answer' => $results[0]['Project']['User'][3]['security_answer'],
							'language_id' => 1,
							'timezone_id' => 1,
							'ip' => '127.0.0.1',
							'admin' => 0,
							'type' => 'user',
							'vivo' => null,
							'ProjectsUsers' => array(
								'id' => $results[0]['Project']['User'][3]['ProjectsUsers']['id'],
								'project_id' => $results[0]['Project']['User'][3]['ProjectsUsers']['project_id'],
								'user_id' => $results[0]['Project']['User'][3]['id'],
								'role_id' => 5,
								'newrole_id' => 0,
							),
						),
					),
				),
				'SiteRole' => array(
					'id' => $results[0]['SiteRole']['id'],
					'name' => 'project.manager',
					'type' => 'project',
					'rank' => 0,
				),
				'Role' => array(
					'id' => $results[0]['Role']['id'],
					'table_type' => 'project',
					'table_id' => $results[0]['Role']['table_id'],
					'name' => 'Manager',
					'read_only' => 0,
				),
			),
			array(
				'ProjectsUsers' => array(
					'id' => $results[1]['ProjectsUsers']['id'],
					'project_id' => 1,
					'user_id' => $results[1]['ProjectsUsers']['user_id'],
					'role_id' => 5,
					'newrole_id' => 8,
				),
				'User' => array(
					'id' => $results[1]['User']['id'],
					'username' => 'testuser',
					'password' => $results[1]['User']['password'],
					'email' => 'testuser@example.com',
					'alt_email' => 'testtest@example.com',
					'prefix' => 'Mr.',
					'first_name' => 'Test',
					'last_name' => 'User',
					'name' => 'Test User',
					'suffix' => 'Esq.',
					'title' => 'Programmer',
					'description' => 'test',
					'status' => 'Test',
					'gender' => 'male',
					'age' => 50,
					'picture' => null,
					'privacy' => 'private',
					'activity' => $results[1]['User']['activity'],
					'registered' => $results[1]['User']['registered'],
					'hash' => $results[1]['User']['hash'],
					'private_hash' => $results[1]['User']['private_hash'],
					'auth_token' => 'AAAAA',
					'auth_timestamp' => 1269625040,
					'confirmed' => 1,
					'changepass' => 0,
					'security_question' => 1,
					'security_answer' => $results[1]['User']['security_answer'],
					'language_id' => 1,
					'timezone_id' => 1,
					'ip' => '127.0.0.1',
					'admin' => 0,
					'type' => 'user',
					'vivo' => null,
				),
				'Project' => array(
					'id' => $results[1]['Project']['id'],
					'name' => 'Private Test Project',
					'description' => 'Private Test Project',
					'privacy' => 'private',
					'picture' => null,
					'email' => 'testprj+private@example.com',
					'created' => $results[1]['Project']['created'],
					'User' => array(
						array(
							'id' => $results[1]['Project']['User'][0]['id'],
							'username' => 'anotheruser',
							'password' => $results[0]['Project']['User'][0]['password'],
							'email' => 'anotheruser@example.com',
							'alt_email' => 'anothertest@example.com',
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
							'activity' => $results[1]['Project']['User'][0]['activity'],
							'registered' => $results[1]['Project']['User'][0]['registered'],
							'hash' => $results[1]['Project']['User'][0]['hash'],
							'private_hash' => $results[1]['Project']['User'][0]['private_hash'],
							'auth_token' => 'AAAAA',
							'auth_timestamp' => 1269625040,
							'confirmed' => 1,
							'changepass' => 0,
							'security_question' => 1,
							'security_answer' => $results[1]['Project']['User'][0]['security_answer'],
							'language_id' => 1,
							'timezone_id' => 1,
							'ip' => '127.0.0.1',
							'admin' => 0,
							'type' => 'user',
							'vivo' => null,
							'ProjectsUsers' => array(
								'id' => $results[1]['Project']['User'][0]['ProjectsUsers']['id'],
								'project_id' => $results[1]['Project']['User'][0]['ProjectsUsers']['project_id'],
								'user_id' => $results[1]['Project']['User'][0]['id'],
								'role_id' => 6,
								'newrole_id' => 9,
							),
						),
						array(
							'id' => $results[1]['Project']['User'][1]['id'],
							'username' => 'testuser',
							'password' => $results[1]['Project']['User'][1]['password'],
							'email' => 'testuser@example.com',
							'alt_email' => 'testtest@example.com',
							'prefix' => 'Mr.',
							'first_name' => 'Test',
							'last_name' => 'User',
							'name' => 'Test User',
							'suffix' => 'Esq.',
							'title' => 'Programmer',
							'description' => 'test',
							'status' => 'Test',
							'gender' => 'male',
							'age' => 50,
							'picture' => null,
							'privacy' => 'private',
							'activity' => $results[1]['Project']['User'][1]['activity'],
							'registered' => $results[1]['Project']['User'][1]['registered'],
							'hash' => $results[1]['Project']['User'][1]['hash'],
							'private_hash' => $results[1]['Project']['User'][1]['private_hash'],
							'auth_token' => 'AAAAA',
							'auth_timestamp' => 1269625040,
							'confirmed' => 1,
							'changepass' => 0,
							'security_question' => 1,
							'security_answer' => $results[1]['Project']['User'][1]['security_answer'],
							'language_id' => 1,
							'timezone_id' => 1,
							'ip' => '127.0.0.1',
							'admin' => 0,
							'type' => 'user',
							'vivo' => null,
							'ProjectsUsers' => array(
								'id' => $results[1]['Project']['User'][1]['ProjectsUsers']['id'],
								'project_id' => $results[1]['Project']['User'][1]['ProjectsUsers']['project_id'],
								'user_id' => $results[1]['Project']['User'][1]['id'],
								'role_id' => 5,
								'newrole_id' => 8,
							),
						),
					),
				),
				'SiteRole' => array(
					'id' => $results[1]['SiteRole']['id'],
					'name' => 'project.manager',
					'type' => 'project',
					'rank' => 0,
				),
				'Role' => array(
					'id' => $results[1]['Role']['id'],
					'table_type' => 'project',
					'table_id' => $results[1]['Role']['table_id'],
					'name' => 'Manager',
					'read_only' => 0,
				),
			),
		);

		$this->assertEqual($results, $expected);
	}

	function testProjectsNullUserId()
	{
		$user_id = null;

		try
		{
			$results = $this->ProjectsUsers->projects($user_id);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testProjectsInvalidUserId()
	{
		$user_id = 'invalid';

		try
		{
			$results = $this->ProjectsUsers->projects($user_id);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testUsers()
	{
		$project_id = 1;

		$results = $this->ProjectsUsers->users($project_id);

		$expected = array(
			array(
				'ProjectsUsers' => array(
					'id' => $results[0]['ProjectsUsers']['id'],
					'project_id' => $results[0]['ProjectsUsers']['project_id'],
					'user_id' => $results[0]['User']['id'],
					'role_id' => 6,
					'newrole_id' => 9,
				),
				'User' => array(
					'id' => $results[0]['User']['id'],
					'username' => 'anotheruser',
					'password' => 'hash',
					'id' => $results[0]['User']['id'],
					'email' => 'anotheruser@example.com',
					'alt_email' => 'anothertest@example.com',
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
					'activity' => $results[0]['User']['activity'],
					'registered' => $results[0]['User']['registered'],
					'hash' => $results[0]['User']['hash'],
					'private_hash' => $results[0]['User']['private_hash'],
					'auth_token' => 'AAAAA',
					'auth_timestamp' => 1269625040,
					'confirmed' => 1,
					'changepass' => 0,
					'security_question' => 1,
					'security_answer' => $results[0]['User']['security_answer'],
					'language_id' => 1,
					'timezone_id' => 1,
					'ip' => '127.0.0.1',
					'admin' => 0,
					'type' => 'user',
					'vivo' => null,
				),
				'Project' => array(
					'id' => $results[0]['Project']['id'],
					'name' => 'Private Test Project',
					'description' => 'Private Test Project',
					'privacy' => 'private',
					'picture' => null,
					'email' => 'testprj+private@example.com',
					'created' => $results[0]['Project']['created'],
				),
				'SiteRole' => array(
					'id' => $results[0]['SiteRole']['id'],
					'name' => 'project.member',
					'type' => 'project',
					'rank' => 1,
				),
				'Role' => array(
					'id' => $results[0]['Role']['id'],
					'table_type' => 'project',
					'table_id' => $results[0]['Role']['table_id'],
					'name' => 'Member',
					'read_only' => 0,
				),
			),
			array(
				'ProjectsUsers' => array(
					'id' => $results[1]['ProjectsUsers']['id'],
					'project_id' => $results[1]['ProjectsUsers']['project_id'],
					'user_id' => $results[1]['User']['id'],
					'role_id' => 5,
					'newrole_id' => 8,
				),
				'User' => array(
					'id' => $results[1]['User']['id'],
					'username' => 'testuser',
					'password' => $results[1]['User']['password'],
					'email' => 'testuser@example.com',
					'alt_email' => 'testtest@example.com',
					'prefix' => 'Mr.',
					'first_name' => 'Test',
					'last_name' => 'User',
					'name' => 'Test User',
					'suffix' => 'Esq.',
					'title' => 'Programmer',
					'description' => 'test',
					'status' => 'Test',
					'gender' => 'male',
					'age' => 50,
					'picture' => null,
					'privacy' => 'private',
					'activity' => $results[1]['User']['activity'],
					'registered' => $results[1]['User']['registered'],
					'hash' => $results[1]['User']['hash'],
					'private_hash' => $results[1]['User']['private_hash'],
					'auth_token' => 'AAAAA',
					'auth_timestamp' => 1269625040,
					'confirmed' => 1,
					'changepass' => 0,
					'security_question' => 1,
					'security_answer' => $results[1]['User']['security_answer'],
					'language_id' => 1,
					'timezone_id' => 1,
					'ip' => '127.0.0.1',
					'admin' => 0,
					'type' => 'user',
					'vivo' => null,
				),
				'Project' => array(
					'id' => $results[1]['Project']['id'],
					'name' => 'Private Test Project',
					'description' => 'Private Test Project',
					'privacy' => 'private',
					'picture' => null,
					'email' => 'testprj+private@example.com',
					'created' => $results[1]['Project']['created'],
				),
				'SiteRole' => array(
					'id' => $results[1]['SiteRole']['id'],
					'name' => 'project.manager',
					'type' => 'project',
					'rank' => 0,
				),
				'Role' => array(
					'id' => $results[1]['Role']['id'],
					'table_type' => 'project',
					'table_id' => $results[1]['Role']['table_id'],
					'name' => 'Manager',
					'read_only' => 0,
				),
			),
		);

		$this->assertEqual($results, $expected);
	}

	function testUsersNullProjectId()
	{
		$project_id = null;

		try
		{
			$results = $this->ProjectsUsers->users($project_id);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testUsersInvalidProjectId()
	{
		$project_id = 'invalid';

		try
		{
			$results = $this->ProjectsUsers->users($project_id);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testManagers()
	{
		$project_id = 1;

		$results = $this->ProjectsUsers->managers($project_id);

		$expected = array(
			array(
				'ProjectsUsers' => array(
					'id' => $results[0]['ProjectsUsers']['id'],
					'project_id' => $results[0]['ProjectsUsers']['project_id'],
					'user_id' => $results[0]['User']['id'],
					'role_id' => 5,
					'newrole_id' => 8,
				),
				'User' => array(
					'id' => $results[0]['User']['id'],
					'username' => 'testuser',
					'password' => $results[0]['User']['password'],
					'email' => 'testuser@example.com',
					'alt_email' => 'testtest@example.com',
					'prefix' => 'Mr.',
					'first_name' => 'Test',
					'last_name' => 'User',
					'name' => 'Test User',
					'suffix' => 'Esq.',
					'title' => 'Programmer',
					'description' => 'test',
					'status' => 'Test',
					'gender' => 'male',
					'age' => 50,
					'picture' => null,
					'privacy' => 'private',
					'activity' => $results[0]['User']['activity'],
					'registered' => $results[0]['User']['registered'],
					'hash' => $results[0]['User']['hash'],
					'private_hash' => $results[0]['User']['private_hash'],
					'auth_token' => 'AAAAA',
					'auth_timestamp' => 1269625040,
					'confirmed' => 1,
					'changepass' => 0,
					'security_question' => 1,
					'security_answer' => $results[0]['User']['security_answer'],
					'language_id' => 1,
					'timezone_id' => 1,
					'ip' => '127..0.1',
					'admin' => 0,
					'type' => 'user',
					'vivo' => null,
				),
				'Project' => array(
					'id' => $results[0]['Project']['id'],
					'name' => 'Private Test Project',
					'description' => 'Private Test Project',
					'privacy' => 'private',
					'picture' => null,
					'email' => 'testprj+private@example.com',
					'created' => $results[0]['Project']['created'],
				),
				'SiteRole' => array(
					'id' => $results[0]['SiteRole']['id'],
					'name' => 'project.manager',
					'type' => 'project',
					'rank' => 0,
				),
				'Role' => array(
					'id' => $results[0]['Role']['id'],
					'table_type' => 'project',
					'table_id' => $results[0]['Role']['table_id'],
					'name' => 'Manager',
					'read_only' => 0,
				),
			),
		);
				
		$this->assertEqual($results, $expected);
	}

	function testManagersNullProjectId()
	{
		$project_id = null;

		try
		{
			$results = $this->ProjectsUsers->managers($project_id);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testManagersInvalidProjectId()
	{
		$project_id = 'invalid';

		try
		{
			$results = $this->ProjectsUsers->managers($project_id);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testMembers()
	{
		$project_id = 1;

		$results = $this->ProjectsUsers->members($project_id);

		$expected = array(
			array(
				'ProjectsUsers' => array(
					'id' => $results[0]['ProjectsUsers']['id'],
					'project_id' => $results[0]['ProjectsUsers']['project_id'],
					'user_id' => $results[0]['User']['id'],
					'role_id' => 6,
					'newrole_id' => 9,
				),
				'User' => array(
					'id' => $results[0]['User']['id'],
					'username' => 'anotheruser',
					'password' => 'hash',
					'id' => $results[0]['User']['id'],
					'email' => 'anotheruser@example.com',
					'alt_email' => 'anothertest@example.com',
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
					'activity' => $results[0]['User']['activity'],
					'registered' => $results[0]['User']['registered'],
					'hash' => $results[0]['User']['hash'],
					'private_hash' => $results[0]['User']['private_hash'],
					'auth_token' => 'AAAAA',
					'auth_timestamp' => 1269625040,
					'confirmed' => 1,
					'changepass' => 0,
					'security_question' => 1,
					'security_answer' => $results[0]['User']['security_answer'],
					'language_id' => 1,
					'timezone_id' => 1,
					'ip' => '127.0.0.1',
					'admin' => 0,
					'type' => 'user',
					'vivo' => null,
				),
				'Project' => array(
					'id' => $results[0]['Project']['id'],
					'name' => 'Private Test Project',
					'description' => 'Private Test Project',
					'privacy' => 'private',
					'picture' => null,
					'email' => 'testprj+private@example.com',
					'created' => $results[0]['Project']['created'],
				),
				'SiteRole' => array(
					'id' => $results[0]['SiteRole']['id'],
					'name' => 'project.member',
					'type' => 'project',
					'rank' => 1,
				),
				'Role' => array(
					'id' => $results[0]['Role']['id'],
					'table_type' => 'project',
					'table_id' => $results[0]['Role']['table_id'],
					'name' => 'Member',
					'read_only' => 0,
				),
			),
		);
				
		$this->assertEqual($results, $expected);
	}

	function testMembersNullProjectId()
	{
		$project_id = null;

		try
		{
			$results = $this->ProjectsUsers->members($project_id);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testMembersInvalidProjectId()
	{
		$project_id = 'invalid';

		try
		{
			$results = $this->ProjectsUsers->members($project_id);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}
}
?>
