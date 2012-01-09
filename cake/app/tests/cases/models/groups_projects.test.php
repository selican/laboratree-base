<?php 
/* SVN FILE: $Id$ */
/* GroupsProject Test cases generated on: 2010-12-20 14:57:05 : 1292857025*/
App::import('Model', 'GroupsProjects');

class GroupsProjectsTestCase extends CakeTestCase {
	var $GroupsProjects = null;
	var $fixtures = array('app.helps', 'app.app_category', 'app.app_data', 'app.application', 'app.app_module', 'app.attachment', 'app.discussion', 'app.doc', 'app.docs_permission', 'app.docs_tag', 'app.docs_type_data', 'app.docs_type_field', 'app.docs_type', 'app.docs_type_row', 'app.docs_version', 'app.group', 'app.groups_address', 'app.groups_association', 'app.groups_award', 'app.groups_interest', 'app.groups_phone', 'app.groups_projects', 'app.groups_publication', 'app.groups_setting', 'app.groups_url', 'app.groups_users', 'app.inbox', 'app.inbox_hash', 'app.interest', 'app.message_archive', 'app.message', 'app.note', 'app.ontology_concept', 'app.preference', 'app.project', 'app.projects_association', 'app.projects_interest', 'app.projects_setting', 'app.projects_url', 'app.projects_users', 'app.role', 'app.setting', 'app.site_role', 'app.tag', 'app.type', 'app.url', 'app.user', 'app.users_address', 'app.users_association', 'app.users_award', 'app.users_education', 'app.users_interest', 'app.users_job', 'app.users_phone', 'app.users_preference', 'app.users_publication', 'app.users_url');

	function startTest() {
		$this->GroupsProjects =& ClassRegistry::init('GroupsProjects');
	}

	function testGroupsProjectsInstance() {
		$this->assertTrue(is_a($this->GroupsProjects, 'GroupsProjects'));
	}

	function testGroupsProjectsFind() {
		$this->GroupsProjects->recursive = -1;
		$results = $this->GroupsProjects->find('first');
		$this->assertTrue(!empty($results));

		$expected = array('GroupsProjects' => array(
			'id'  => 1,
			'group_id'  => 1,
			'project_id'  => 1
		));
		$this->assertEqual($results, $expected);
	}

	function testGroups()
	{
		$project_id = 1;

		$node = $this->GroupsProjects->groups($project_id);
		$expected = array(
			array(
				'GroupsProjects' => array(
					'id' => 1,
					'group_id' => 1,
					'project_id' => 1,
				),
				'Group' => array(
					'id' => 1,
					'name' => 'Private Test Group',
					'email' => 'testgrp+private@example.com',
					'description' => 'Test Group',
					'privacy' => 'private',
					'picture' => null,
					'created' => $node[0]['Group']['created'],
					'User' => array(
						array(
							'id' => 2,
							'username' => 'anotheruser',
							'password' => $node[0]['Group']['User'][0]['password'],
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
							'activity' => $node[0]['Group']['User'][0]['activity'],
							'registered' => $node[0]['Group']['User'][0]['registered'],
							'hash' => $node[0]['Group']['User'][0]['hash'],
							'private_hash' => $node[0]['Group']['User'][0]['private_hash'],
							'auth_token' => 'AAAAA',
							'auth_timestamp' => 1269625040,
							'confirmed' => 1,
							'changepass' => 0,
							'security_question' => 1,
							'security_answer' => $node[0]['Group']['User'][0]['security_answer'],
							'language_id' => 1,
							'timezone_id' => 1,
							'ip' => '127.0.0.1',
							'admin' => 0,
							'type' => 'user',
							'vivo' => null,
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
							'username' => 'testuser',
							'password' => $node[0]['Group']['User'][1]['password'],
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
							'activity' => $node[0]['Group']['User'][1]['activity'],
							'registered' => $node[0]['Group']['User'][1]['registered'],
							'hash' => $node[0]['Group']['User'][1]['hash'],
							'private_hash' => $node[0]['Group']['User'][1]['private_hash'],
							'auth_token' => 'AAAAA',
							'auth_timestamp' => 1269625040,
							'confirmed' => 1,
							'changepass' => 0,
							'security_question' => 1,
							'security_answer' => $node[0]['Group']['User'][1]['security_answer'],
							'language_id' => 1,
							'timezone_id' => 1,
							'ip' => '127.0.0.1',
							'admin' => 0,
							'type' => 'user',
							'vivo' => null,
							'GroupsUsers' => array(
								'id' => 1,
								'group_id' => 1,
								'user_id' => 1,
								'role_id' => 1,
								'newrole_id' => 2,
							),
						),
					),
				),
				'Project' => array(
					'id' => 1,
					'name' => 'Private Test Project',
					'description' => 'Private Test Project',
					'privacy' => 'private',
					'picture' => null,
					'email' => 'testprj+private@example.com',
					'created' => $node[0]['Project']['created'],
				),
			),
		);

		$this->assertEqual($node, $expected);
	}

	function testGroupsNullProjectId()
	{
		$project_id = null;

		try
		{
			$node = $this->GroupsProjects->groups($project_id);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testGroupsInvalidProjectId()
	{
		$project_id = 'invalid';

		try
		{
			$node = $this->GroupsProjects->groups($project_id);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testProjects()
	{
		$group_id = 1;

		$results = $this->GroupsProjects->projects($group_id);

		$expected = array(
			array(
				'GroupsProjects' => array(
					'id' => 1,
					'group_id' => 1,
					'project_id' => 1,
				),
				'Group' => array(
					'id' => 1,
					'name' => 'Private Test Group',
					'email' => 'testgrp+private@example.com',
					'description' => 'Test Group',
					'privacy' => 'private',
					'picture' => null,
					'created' => $results[0]['Group']['created'],
				),
				'Project' => array(
					'id' => 1,
					'name' => 'Private Test Project',
					'description' => 'Private Test Project',
					'privacy' => 'private',
					'picture' => null,
					'email' => 'testprj+private@example.com',
					'created' => $results[0]['Project']['created'],
					'User' => array(
						array(
							'id' => 2,
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
								'id' => 2,
								'project_id' => 1,
								'user_id' => 2,
								'role_id' => 6,
								'newrole_id' => 9,
							),
						),
						array(
							'id' => 1,
							'username' => 'testuser',
							'password' => $results[0]['Project']['User'][1]['password'],
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
								'id' => 1,
								'project_id' => 1,
								'user_id' => 1,
								'role_id' => 5,
								'newrole_id' => 8,
							),
						),
					),
				),
			),
		);

		$this->assertEqual($results, $expected);
	}

	function testProjectsNullGroupId()
	{
		$group_id = null;

		try
		{
			$node = $this->GroupsProjects->projects($group_id);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testProjectsInvalidGroupId()
	{
		$group_id = 'invalid';

		try
		{
			$node = $this->GroupsProjects->projects($group_id);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

}
?>
