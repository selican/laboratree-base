<?php 
App::import('Model', 'App');
App::import('Model', 'Group');
App::import('vendor', 'ZendLucene', array('file' => 'Zend' . DS . 'Search' . DS . 'Lucene.php'));

if(!class_exists('Zend_Search_Lucene'))
{
	Mock::generate('Zend_Search_Lucene');
}


class AppModelTestModel extends AppModel {

	var $name = 'Test';
	var $useTable = 'users';

	var $html = array('last_name');
}

class AppModelTest extends CakeTestCase {
       var $fixtures = array('app.helps', 'app.app_category', 'app.app_data', 'app.application', 'app.app_module', 'app.attachment', 'app.discussion', 'app.doc', 'app.docs_permission', 'app.docs_tag', 'app.docs_type_data', 'app.docs_type_field', 'app.docs_type', 'app.docs_type_row', 'app.docs_version', 'app.group', 'app.groups_address', 'app.groups_association', 'app.groups_award', 'app.groups_interest', 'app.groups_phone', 'app.groups_projects', 'app.groups_publication', 'app.groups_setting', 'app.groups_url', 'app.groups_users', 'app.inbox', 'app.inbox_hash', 'app.interest', 'app.message_archive', 'app.message', 'app.note', 'app.ontology_concept', 'app.preference', 'app.project', 'app.projects_association', 'app.projects_interest', 'app.projects_setting', 'app.projects_url', 'app.projects_users', 'app.role', 'app.setting', 'app.site_role', 'app.tag', 'app.type', 'app.url', 'app.user', 'app.users_address', 'app.users_association', 'app.users_award', 'app.users_education', 'app.users_interest', 'app.users_job', 'app.users_phone', 'app.users_preference', 'app.users_publication', 'app.users_url');

	var $App = null;

	function startTest() {
		$this->App = new AppModelTestModel();
                $this->Group =& ClassRegistry::init('Group');

//		$this->Model = new TestGroupModel();
	}

	function testAppModelInstance() {
		$this->assertTrue(is_a($this->App, 'AppModel'));
	}

        function testGroupInstance() {
                $this->assertTrue(is_a($this->Group, 'Group'));
        }
	
	function testGetById() {
		$this->App->getById(null);
	}

	function testFlattenEmptyData() {
	//	$this->App->flatten(null);
	}
/*
	function testFlattenValueArray() {
		$this->App->flatten(array(
					'value' => 'value',
					), null);
	}
*/
	function testToList()
	{
		$root = 'root';
		$data = array(
			array(
				'User' => array(
					'name' => 'value'
				),
				'Project' => array(
					'name' => 'value'
				),
			),
		);

		$results = $this->App->toList($root, $data);
		$expected = array(
			'success' => 1,
			'root' => array(
				array(
					'user_name' => 'value',
					'project_name' => 'value',
				),
			),
		);

		$this->assertEqual($results, $expected);
	}

	function testToListNullRoot()
	{
		$root = null;
		$data = array(
			array(
				'User' => array(
					'name' => 'value'
				),
				'Project' => array(
					'name' => 'value'
				),
			),
		);

		try
		{
			$results = $this->App->toList($root, $data);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testToListInvalidRoot()
	{
		$root = array();
		$data = array(
			array(
				'User' => array(
					'name' => 'value'
				),
				'Project' => array(
					'name' => 'value'
				),
			),
		);

		try
		{
			$results = $this->App->toList($root, $data);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testToListNullData()
	{
		$root = 'root';
		$data = null;

		try
		{
			$results = $this->App->toList($root, $data);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testToListInvalidData()
	{
		$root = 'root';
		$data = 'invalid';

		try
		{
			$results = $this->App->toList($root, $data);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testToNodes()
	{
		$data = array(
			array(
				'User' => array(
					'name' => 'value'
				),
				'Project' => array(
					'name' => 'value'
				),
			),
		);

		$results = $this->App->toNodes($data);
		$expected = array(
			array(
				'user_name' => 'value',
				'project_name' => 'value',
			),
		);

		$this->assertEqual($results, $expected);
	}

	function testToNodesNullData()
	{
		$data = null;

		try
		{
			$results = $this->App->toNodes($data);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testToNodesInvalidData()
	{
		$data = 'invalid';

		try
		{
			$results = $this->App->toNodes($data);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testToNode()
	{
		$data = array(
			'User' => array(
				'name' => 'value'
			),
			'Project' => array(
				'name' => 'value'
			),
		);

		$results = $this->App->toNode($data);
		$expected = array(
			'user_name' => 'value',
			'project_name' => 'value',
		);

		$this->assertEqual($results, $expected);
	}

	function testToNodeNullData()
	{
		$data = null;

		try
		{
			$results = $this->App->toNode($data);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testToNodeInvalidData()
	{
		$data = 'invalid';

		try
		{
			$results = $this->App->toNode($data);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testAfterDelete() {
		$this->App->afterDelete();
	}

	function testBeforeSaveValidTag() {
		$this->App->data = array(
			'Test' => array(
				'last_name' => '<b>Test</b>',
			),
		);

		$this->App->beforeSave();

		$this->assertEqual($this->App->data, array(
			'Test' => array(
				'last_name' => '<b>Test</b>',
			),
		));
	}

	function testBeforeSaveValidAttr() {
		$this->App->data = array(
			'Test' => array(
				'last_name' => '<b align="center">Test</b>',
			),
		);

		$this->App->beforeSave();

		$this->assertEqual($this->App->data, array(
			'Test' => array(
				'last_name' => '<b align="center">Test</b>',
			),
		));
	}


	function testBeforeSaveInvalidTag() {
		$this->App->data = array(
			'Test' => array(
				'last_name' => '<script>Test</script>',
			),
		);

		$this->App->beforeSave();

		$this->assertEqual($this->App->data, array(
			'Test' => array(
				'last_name' => 'Test',
			),
		));
	}

	function testBeforeSaveInvalidAttr() {
		$this->App->data = array(
			'Test' => array(
				'last_name' => '<b onmouseover="script;">Test</b>',
			),
		);

		$this->App->beforeSave();

		$this->assertEqual($this->App->data, array(
			'Test' => array(
				'last_name' => '<b>Test</b>',
			),
		));
	}

	function testBeforeSaveMixedTag() {
		$this->App->data = array(
			'Test' => array(
				'last_name' => '<b>Test</b><script>Script</script>',
			),
		);

		$this->App->beforeSave();

		$this->assertEqual($this->App->data, array(
			'Test' => array(
				'last_name' => '<b>Test</b>Script',
			),
		));
	}

	function testBeforeSaveMixedAttr() {
		$this->App->data = array(
			'Test' => array(
				'last_name' => '<b onmouseover="script;" align="center">Test</b>',
			),
		);

		$this->App->beforeSave();

		$this->assertEqual($this->App->data, array(
			'Test' => array(
				'last_name' => '<b align="center">Test</b>',
			),
		));
	}


	function testBeforeSaveSanity() {
		$this->App->data = array(
			'Test' => array(
				'name' => 'Test',
			),
		);

		$this->App->beforeSave();

		$this->assertEqual($this->App->data, array(
			'Test' => array(
				'name' => 'Test',
			),
		));
	}


	function testBeforeSaveSanitize() {
		$this->App->data = array(
			'Test' => array(
				'name' => '<b>Test</b>',
			),
		);

		$this->App->beforeSave();

		$this->assertEqual($this->App->data, array(
			'Test' => array(
				'name' => 'Test',
			),
		));
	}

	function testToKeyword()
	{
		$name = 'Test One';
		$keyword = $this->App->toKeyword($name);
		$expected = 'testone';
		$this->assertEqual($keyword, $expected);
	}

	function testToKeywordNullName()
	{
		$name = null;
		try
		{
			$keyword = $this->App->toKeyword($name);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testFormatSizeGB() {
		if ($this->App->formatSize(1073741824) != 1)
			$this->fail();
	}

	function testFormatSizeMB() {
                if ($this->App->formatSize(1048576) != 1)
                        $this->fail();
	}
	
	function testFormatSizeKB() {
                if ($this->App->formatSize(1024) != 1)
                        $this->fail();
	}

	function testFormatSizeB() {
		try {
                	$this->App->formatSize(0);
                        $this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}
	}

	function testToLucenDocNullModel() {
//		if ($this->App->toLuceneDoc(null, null, null, null) != null)
//                        $this->fail();
	}

	function testToLuceneDocNullDoc() {
		$this->App->toLuceneDoc(&$Group, null, null, null);
	}

	function testIntegerToFieldID() {
//		$this->assertEqual($this->App->_integerToField(null, 'id', null), null);

                $this->assertEqual($this->App->_integerToField(null, 'id', null), Zend_Search_Lucene_Field::Text('_id', null));
	}

	function endTest() {
		unset($this->App);
		@unlink('Zend_Search_Lucene');
	}
}
?>
