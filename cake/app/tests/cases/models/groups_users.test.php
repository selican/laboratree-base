<?php 
/* SVN FILE: $Id$ */
/* GroupsUsers Test cases generated on: 2010-12-20 14:57:37 : 1292857057*/
App::import('Model', 'GroupsUsers');

class GroupsUsersTestCase extends CakeTestCase {
	var $GroupsUsers = null;
	var $fixtures = array('app.helps', 'app.app_category', 'app.app_data', 'app.application', 'app.app_module', 'app.attachment', 'app.discussion', 'app.doc', 'app.docs_permission', 'app.docs_tag', 'app.docs_type_data', 'app.docs_type_field', 'app.docs_type', 'app.docs_type_row', 'app.docs_version', 'app.group', 'app.groups_address', 'app.groups_association', 'app.groups_award', 'app.groups_interest', 'app.groups_phone', 'app.groups_projects', 'app.groups_publication', 'app.groups_setting', 'app.groups_url', 'app.groups_users', 'app.inbox', 'app.inbox_hash', 'app.interest', 'app.message_archive', 'app.message', 'app.note', 'app.ontology_concept', 'app.preference', 'app.project', 'app.projects_association', 'app.projects_interest', 'app.projects_setting', 'app.projects_url', 'app.projects_users', 'app.role', 'app.setting', 'app.site_role', 'app.tag', 'app.type', 'app.url', 'app.user', 'app.users_address', 'app.users_association', 'app.users_award', 'app.users_education', 'app.users_interest', 'app.users_job', 'app.users_phone', 'app.users_preference', 'app.users_publication', 'app.users_url');

	function startTest() {
		$this->GroupsUsers =& ClassRegistry::init('GroupsUsers');
	}

	function testGroupsUsersInstance() {
		$this->assertTrue(is_a($this->GroupsUsers, 'GroupsUsers'));
	}

	function testGroupsUsersFind() {
		$this->GroupsUsers->recursive = -1;
		$results = $this->GroupsUsers->find('first');
		$this->assertTrue(!empty($results));

		$expected = array('GroupsUsers' => array(
			'id'  => 1,
			'group_id'  => 1,
			'user_id'  => 1,
			'role_id'  => 1,
			'newrole_id'  => 2
		));
		$this->assertEqual($results, $expected);
	}

	// TODO: Generate the expected for this function after we
	// re-evaluate making the returned results smaller.
	/*
	function testGroups()
	{
		$user_id = 1;

		$results = $this->GroupsUsers->groups($user_id);

		$expected = array(
		);

		$this->assertEqual($results, $expected);
	}
	*/

	function testGroupsNullUserId()
	{
		$user_id = null;

		try
		{
			$results = $this->GroupsUsers->groups($user_id);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testGroupsInvalidUserId()
	{
		$user_id = 'invalid';

		try
		{
			$results = $this->GroupsUsers->groups($user_id);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testGroupsGood() {
		$user_id = '102';

                try
                {
                        $results = $this->GroupsUsers->groups($user_id);
                       // $this->fail('InvalidArgumentException was expected.');
                }
                catch(InvalidArgumentException $e)
                {
                        $this->fail();
                }
        }

	function testUsers()
	{
		$group_id = 1;

		$results = $this->GroupsUsers->users($group_id);
		$expected = array(
			array(
				'GroupsUsers' => array(
					'id' => 2,
					'group_id' => 1,
					'user_id' => 2,
					'role_id' => 2,
					'newrole_id' => 3,
				),
				'User' => array(
					'id' => 2,
					'username' => 'anotheruser',
					'password' => $results[0]['User']['password'],
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
					'security_answer' => 'hash',
					'language_id' => 1,
					'timezone_id' => 1,
					'ip' => '127.0.0.1',
					'admin' => 0,
					'type' => 'user',
					'vivo' => null,
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

				'SiteRole' => array(
					'id' => 2,
					'name' => 'group.member',
					'type' => 'group',
					'rank' => 1,
				),
				'Role' => array(
					'id' => 3,
					'table_type' => 'group',
					'table_id' => 1,
					'name' => 'Member',
					'read_only' => 0,
				),
			),
			array(
				'GroupsUsers' => array(
					'id' => 1,
					'group_id' => 1,
					'user_id' => 1,
					'role_id' => 1,
					'newrole_id' => 2,
				),
				'User' => array(
					'id' => 1,
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
					'security_answer' => 'hash',
					'language_id' => 1,
					'timezone_id' => 1,
					'ip' => '127.0.0.1',
					'admin' => 0,
					'type' => 'user',
					'vivo' => null,
				),
				'Group' => array(
					'id' => 1,
					'name' => 'Private Test Group',
					'email' => 'testgrp+private@example.com',
					'description' => 'Test Group',
					'privacy' => 'private',
					'picture' => null,
					'created' => $results[1]['Group']['created'],
				),
				'SiteRole' => array(
					'id' => 1,
					'name' => 'group.manager',
					'type' => 'group',
					'rank' => 0,
				),
				'Role' => array(
					'id' => 2,
					'table_type' => 'group',
					'table_id' => 1,
					'name' => 'Manager',
					'read_only' => 0,
				),
			),
		);

		$this->assertEqual($results, $expected);
	}

	function testUsersNullGroupId()
	{
		$group_id = null;

		try
		{
			$results = $this->GroupsUsers->users($group_id);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testUsersInvalidGroupId()
	{
		$group_id = 'invalid';

		try
		{
			$results = $this->GroupsUsers->users($group_id);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testManagers()
	{
		$group_id = 1;

		$results = $this->GroupsUsers->managers($group_id);
		$expected = array(
			array(
				'GroupsUsers' => array(
					'id' => 1,
					'group_id' => 1,
					'user_id' => 1,
					'role_id' => 1,
					'newrole_id' => 2,
				),
				'User' => array(
					'id' => 1,
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
					'security_answer' => 'hash',
					'language_id' => 1,
					'timezone_id' => 1,
					'ip' => '127.0.0.1',
					'admin' => 0,
					'type' => 'user',
					'vivo' => null,
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
				'SiteRole' => array(
					'id' => 1,
					'name' => 'group.manager',
					'type' => 'group',
					'rank' => 0,
				),
				'Role' => array(
					'id' => 2,
					'table_type' => 'group',
					'table_id' => 1,
					'name' => 'Manager',
					'read_only' => 0,
				),

			),
		);

		$this->assertEqual($results, $expected);
	}

	function testManagersNullGroupId()
	{
		$group_id = null;

		try
		{
			$results = $this->GroupsUsers->managers($group_id);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testManagersInvalidGroupId()
	{
		$group_id = 'invalid';

		try
		{
			$results = $this->GroupsUsers->managers($group_id);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testMembers()
	{
		$group_id = 1;

		$results = $this->GroupsUsers->members($group_id);

		$expected = array(
			array(
				'GroupsUsers' => array(
					'id' => 2,
					'group_id' => 1,
					'user_id' => 2,
					'role_id' => 2,
					'newrole_id' => 3,
				),
				'User' => array(
					'id' => 2,
					'username' => 'anotheruser',
					'password' => $results[0]['User']['password'],
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
					'security_answer' => 'hash',
					'language_id' => 1,
					'timezone_id' => 1,
					'ip' => '127.0.0.1',
					'admin' => 0,
					'type' => 'user',
					'vivo' => null,
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

				'SiteRole' => array(
					'id' => 2,
					'name' => 'group.member',
					'type' => 'group',
					'rank' => 1,
				),
				'Role' => array(
					'id' => 3,
					'table_type' => 'group',
					'table_id' => 1,
					'name' => 'Member',
					'read_only' => 0,
				),
			),
		);

		$this->assertEqual($results, $expected);
	}

	function testMembersNullGroupId()
	{
		$group_id = null;

		try
		{
			$results = $this->GroupsUsers->members($group_id);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testMembersInvalidGroupId()
	{
		$group_id = 'invalid';

		try
		{
			$results = $this->GroupsUsers->members($group_id);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}
}
?>
