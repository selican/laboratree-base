<?php
App::import('Controller', 'App');
App::import('Component', 'Lucene');

class LuceneComponentTestController extends AppController {
	var $name = 'Test';
	var $uses = array();
	var $components = array(
		'Lucene',
	);
}

class LuceneTest extends CakeTestCase
{
	var $fixtures = array('app.helps', 'app.app_category', 'app.app_data', 'app.application', 'app.app_module', 'app.attachment', 'app.digest', 'app.discussion', 'app.doc', 'app.docs_permission', 'app.docs_tag', 'app.docs_type_data', 'app.docs_type_field', 'app.docs_type', 'app.docs_type_row', 'app.docs_version', 'app.group', 'app.groups_address', 'app.groups_association', 'app.groups_award', 'app.groups_interest', 'app.groups_phone', 'app.groups_projects', 'app.groups_publication', 'app.groups_setting', 'app.groups_url', 'app.groups_users', 'app.inbox', 'app.inbox_hash', 'app.interest', 'app.message_archive', 'app.message', 'app.note', 'app.ontology_concept', 'app.preference', 'app.project', 'app.projects_association', 'app.projects_interest', 'app.projects_setting', 'app.projects_url', 'app.projects_users', 'app.role', 'app.setting', 'app.site_role', 'app.tag', 'app.type', 'app.url', 'app.user', 'app.users_address', 'app.users_association', 'app.users_award', 'app.users_education', 'app.users_interest', 'app.users_job', 'app.users_phone', 'app.users_preference', 'app.users_publication', 'app.users_url', 'app.ldap_user');

	function startTest()
	{
		$this->Controller = new LuceneComponentTestController();
		$this->Controller->constructClasses();
		$this->Controller->Component->initialize($this->Controller);
	}

	function testLuceneInstance() {
		$this->assertTrue(is_a($this->Controller->Lucene, 'LuceneComponent'));
	}

	function testCreate()
	{
		$interface = $this->Controller->Lucene->create();
		$this->assertTrue(is_a($interface, 'Zend_Search_Lucene_Interface'));
	}

	function testGetIndexPath()
	{
		$path = $this->Controller->Lucene->getIndexPath();
		$this->assertEqual($path, TMP . DS . 'index');
	}

	function testSetIndexPath()
	{
		$path = TMP . DS . 'newindex';
		$this->Controller->Lucene->setIndexPath($path);

		$newpath = $this->Controller->Lucene->getIndexPath();
		$this->assertEqual($newpath, $path);
	}

	function testSetIndexPathNullPath()
	{
		$path = null;

		try
		{
			$this->Controller->Lucene->setIndexPath($path);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testSetIndexPathInvalidPath()
	{
		$path = array(
			'invalid' => 'invalid',
		);

		try
		{
			$this->Controller->Lucene->setIndexPath($path);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testGetIndex()
	{
		$index = $this->Controller->Lucene->getIndex();
		$this->assertTrue(is_a($index, 'Zend_Search_Lucene_Interface'));
	}

	function testQuery()
	{
		$query = 'testuser';

		$field = Zend_Search_Lucene_Field::text('query', $query);

		$document = new Zend_Search_Lucene_Document();
		$document->addField($field);

		$index = $this->Controller->Lucene->getIndex();
		$index->addDocument($document);
		
		$results = $this->Controller->Lucene->query($query);
		$this->assertFalse(empty($results));

		$this->assertTrue(is_a($results[0], 'Zend_Search_Lucene_Search_QueryHit'));
		$hit = $results[0]->getDocument()->getFieldValue('query');

		$this->assertEqual($hit, $query);
	}

	function testQueryNullQuery()
	{
		$query = null;

		try
		{
			$this->Controller->Lucene->query($query);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(Exception $e)
		{
			$this->pass();
		}
	}
	
	function testQueryInvalidQuery()
	{
		$query = array(
			'invalid' => 'invalid',
		);

		try
		{
			$this->Controller->Lucene->query($query);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(Exception $e)
		{
			$this->pass();
		}

	}

	function endTest() {
		unset($this->Controller);
		ClassRegistry::flush();	
	}
}
?>
