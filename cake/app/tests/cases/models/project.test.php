<?php 
/* SVN FILE: $Id$ */
/* Project Test cases generated on: 2010-12-20 14:59:06 : 1292857146*/
App::import('Model', 'Project');

class ProjectTestCase extends CakeTestCase {
	var $Project = null;
	var $fixtures = array('app.helps', 'app.app_category', 'app.app_data', 'app.application', 'app.app_module', 'app.attachment', 'app.discussion', 'app.doc', 'app.docs_permission', 'app.docs_tag', 'app.docs_type_data', 'app.docs_type_field', 'app.docs_type', 'app.docs_type_row', 'app.docs_version', 'app.group', 'app.groups_address', 'app.groups_association', 'app.groups_award', 'app.groups_interest', 'app.groups_phone', 'app.groups_projects', 'app.groups_publication', 'app.groups_setting', 'app.groups_url', 'app.groups_users', 'app.inbox', 'app.inbox_hash', 'app.interest', 'app.message_archive', 'app.message', 'app.note', 'app.ontology_concept', 'app.preference', 'app.project', 'app.projects_association', 'app.projects_interest', 'app.projects_setting', 'app.projects_url', 'app.projects_users', 'app.role', 'app.setting', 'app.site_role', 'app.tag', 'app.type', 'app.url', 'app.user', 'app.users_address', 'app.users_association', 'app.users_award', 'app.users_education', 'app.users_interest', 'app.users_job', 'app.users_phone', 'app.users_preference', 'app.users_publication', 'app.users_url');

	function startTest() {
		$this->Project =& ClassRegistry::init('Project');
	}

	function testProjectInstance() {
		$this->assertTrue(is_a($this->Project, 'Project'));
	}

	function testProjectFind() {
		$this->Project->recursive = -1;
		$results = $this->Project->find('first');
		$this->assertTrue(!empty($results));

		$expected = array(
			'Project' => array(
				'id'  => $results['Project']['id'],
				'name'  => 'Private Test Project',
				'description'  => 'Private Test Project',
				'privacy' => 'private',
				'picture'  => null,
				'email'  => 'testprj+private@example.com',
				'created'  => $results['Project']['created'],
			)
		);
		$this->assertEqual($results, $expected);
	}

	function testToNode() {
		$this->Project->recursive = -1;
		$this->Project->contain(array(
			'User',
			'Group',
		));
		$results = $this->Project->find('first');
		$node = $this->Project->toNode($results);

		$expected = array(
			'id'  => $node['id'],
			'name' => 'Private Test Project',
			'text' => 'Private Test Project',
			'leaf' => true,
			'description' => 'Private Test Project',
			'session' => 'group:project_' . $node['id'],
			'token' => 'project:' . $node['id'],
			'type' => 'project',
			'email' => 'testprj+private@example.com',
			'privacy' => 'private',
			'image' => '/img/projects/default_small.png',
			'role' => 'project.manager',
			'members' => 2,
			'group' => 'Group: Private Test Group',
			'group_type' => 'group',
			'group_id' => 1,
		);

		$this->assertEqual($node, $expected);
	}

	function testToNodeNull() {
		try
		{
			$node = $this->Project->toNode(null);
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
			$node = $this->Project->toNode('string');
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
			$node = $this->Project->toNode(array('id' => 1));
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
			$node = $this->Project->toNode(array('Project' => array('test' => 1)));
			$this->fail('InvalidArgumentException was expected');
		}
		catch (InvalidArgumentException $e)
		{
			$this->pass();
		}
	}
}
?>
