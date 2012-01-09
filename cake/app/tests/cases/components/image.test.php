<?php
App::import('Controller', 'App');
App::import('Component', 'Image');

class ImageComponentTestController extends AppController {
	var $name = 'Test';
	var $uses = array();
	var $components = array(
		'Image',
	);
}

class ImageTest extends CakeTestCase
{
	var $fixtures = array('app.helps', 'app.app_category', 'app.app_data', 'app.application', 'app.app_module', 'app.attachment', 'app.digest', 'app.discussion', 'app.doc', 'app.docs_permission', 'app.docs_tag', 'app.docs_type_data', 'app.docs_type_field', 'app.docs_type', 'app.docs_type_row', 'app.docs_version', 'app.group', 'app.groups_address', 'app.groups_association', 'app.groups_award', 'app.groups_interest', 'app.groups_phone', 'app.groups_projects', 'app.groups_publication', 'app.groups_setting', 'app.groups_url', 'app.groups_users', 'app.inbox', 'app.inbox_hash', 'app.interest', 'app.message_archive', 'app.message', 'app.note', 'app.ontology_concept', 'app.preference', 'app.project', 'app.projects_association', 'app.projects_interest', 'app.projects_setting', 'app.projects_url', 'app.projects_users', 'app.role', 'app.setting', 'app.site_role', 'app.tag', 'app.type', 'app.url', 'app.user', 'app.users_address', 'app.users_association', 'app.users_award', 'app.users_education', 'app.users_interest', 'app.users_job', 'app.users_phone', 'app.users_preference', 'app.users_publication', 'app.users_url', 'app.ldap_user');

	function startTest()
	{
		$this->Controller = new ImageComponentTestController();
		$this->Controller->constructClasses();
		$this->Controller->Component->initialize($this->Controller);
	}

	function testImageInstance() {
		$this->assertTrue(is_a($this->Controller->Image, 'ImageComponent'));
	}

	function testStartup() {
//		$this->Controller->Image->startup(&$controller);
	}

	function testCrop()
	{
		$filename = '/data/laboratree/images/test.png';
		$max_height = 2;
		$max_width = 2;

		$result = $this->Controller->Image->crop($filename, $max_height, $max_width);
		$this->assertFalse(empty($result));
	}

	function testCropNullFilename()
	{
		$filename = null;
		$max_height = 2;
		$max_width = 2;

		try
		{
			$result = $this->Controller->Image->crop($filename, $max_height, $max_width);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testCropInvalidFilename()
	{
		$filename = array(
			'invalid' => 'invalid',
		);
		$max_height = 2;
		$max_width = 2;

		try
		{
			$result = $this->Controller->Image->crop($filename, $max_height, $max_width);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}

	}

	function testCropInvalidFilenameNotFound()
	{
		$filename = 'invalid';
		$max_height = 2;
		$max_width = 2;

		try
		{
			$result = $this->Controller->Image->crop($filename, $max_height, $max_width);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}

	}

	function testCropNullMaxHeight()
	{
		$filename = '/data/laboratree/images/test.png';
		$max_height = null;
		$max_width = 2;

		try
		{
			$result = $this->Controller->Image->crop($filename, $max_height, $max_width);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testCropInvalidMaxHeight()
	{
		$filename = '/data/laboratree/images/test.png';
		$max_height = 'invalid';
		$max_width = 2;

		try
		{
			$result = $this->Controller->Image->crop($filename, $max_height, $max_width);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testCropNullMaxWidth()
	{
		$filename = '/data/laboratree/images/test.png';
		$max_height = 2;
		$max_width = null;

		try
		{
			$result = $this->Controller->Image->crop($filename, $max_height, $max_width);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testCropInvalidMaxWidth()
	{
		$filename = '/data/laboratree/images/test.png';
		$max_height = 2;
		$max_width = 'invalid';

		try
		{
			$result = $this->Controller->Image->crop($filename, $max_height, $max_width);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testScale()
	{
		$filename = '/data/laboratree/images/test.png';
		$max_height = 2;

		$result = $this->Controller->Image->scale($filename, $max_height);
		$this->assertFalse(empty($result));
	}

	function testScaleNullFilename()
	{
		$filename = null;
		$max_height = 2;

		try
		{
			$result = $this->Controller->Image->scale($filename, $max_height);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testScaleInvalidFilename()
	{
		$filename = array(
			'invalid' => 'invalid',
		);
		$max_height = 2;

		try
		{
			$result = $this->Controller->Image->scale($filename, $max_height);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}

	}

	function testScaleInvalidFilenameNotFound()
	{
		$filename = 'invalid';
		$max_height = 2;

		try
		{
			$result = $this->Controller->Image->scale($filename, $max_height);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}

	}

	function testScaleNullMaxHeight()
	{
		$filename = '/data/laboratree/images/test.png';
		$max_height = null;

		try
		{
			$result = $this->Controller->Image->scale($filename, $max_height);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testScaleInvalidMaxHeight()
	{
		$filename = '/data/laboratree/images/test.png';
		$max_height = 'invalid';

		try
		{
			$result = $this->Controller->Image->scale($filename, $max_height);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testScaleNullMaxWidth()
	{
		$filename = '/data/laboratree/images/test.png';
		$max_height = 'auto';
		$max_width = null;

		try
		{
			$result = $this->Controller->Image->scale($filename, $max_height, $max_width);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testScaleInvalidMaxWidth()
	{
		$filename = '/data/laboratree/images/test.png';
		$max_height = 'auto';
		$max_width = 'invalid';

		try
		{
			$result = $this->Controller->Image->scale($filename, $max_height, $max_width);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testScaleInvalidMaxValues()
	{
		$filename = '/data/laboratree/images/test.png';
		$max_height = 'auto';
		$max_width = 'auto';

		try
		{
			$result = $this->Controller->Image->scale($filename, $max_height, $max_width);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testUser()
	{
		$tmpfile = '/data/laboratree/images/test.png';
		$filename = $this->Controller->Image->user($tmpfile);

		$image = IMAGES . 'users/' . $filename . '.png';
		$this->assertTrue(file_exists($image));
		unlink($image);

		$thumbnail = IMAGES . 'users/' . $filename . '_thumb.png';
		$this->assertTrue(file_exists($thumbnail));
		unlink($thumbnail);
	}

	function testUserNullTmpFile()
	{
		$tmpfile = null;
		try
		{
			$filename = $this->Controller->Image->user($tmpfile);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testUserInvalidTmpFile()
	{
		$tmpfile = array(
			'invalid' => 'invalid',
		);

		try
		{
			$filename = $this->Controller->Image->user($tmpfile);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
		{
			$this->pass();
		}
	}

	function testUserInvalidTmpFileNotFound()
	{
		$tmpfile = 'invalid';

		try
		{
			$filename = $this->Controller->Image->user($tmpfile);
			$this->fail('InvalidArgumentException was expected.');
		}
		catch(InvalidArgumentException $e)
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
