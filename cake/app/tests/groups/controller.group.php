<?php
class ControllerGroupTest extends GroupTest {
	var $label = 'Component, Controllers, Scaffold test cases.';

	function ControllerGroupTest()
	{
		TestManager::addTestFile($this, TESTS . DS . 'cases' . DS . 'controllers' . DS . 'apps_controller.test.php');
		TestManager::addTestFile($this, TESTS . DS . 'cases' . DS . 'controllers' . DS . 'chat_controller.test.php');
		TestManager::addTestFile($this, TESTS . DS . 'cases' . DS . 'controllers' . DS . 'discussions_controller.test.php');
		TestManager::addTestFile($this, TESTS . DS . 'cases' . DS . 'controllers' . DS . 'docs_controller.test.php');
		TestManager::addTestFile($this, TESTS . DS . 'cases' . DS . 'controllers' . DS . 'groups_controller.test.php');
		TestManager::addTestFile($this, TESTS . DS . 'cases' . DS . 'controllers' . DS . 'inbox_controller.test.php');
		TestManager::addTestFile($this, TESTS . DS . 'cases' . DS . 'controllers' . DS . 'navigation_controller.test.php');
		TestManager::addTestFile($this, TESTS . DS . 'cases' . DS . 'controllers' . DS . 'notes_controller.test.php');
		TestManager::addTestFile($this, TESTS . DS . 'cases' . DS . 'controllers' . DS . 'pages_controller.test.php');
		TestManager::addTestFile($this, TESTS . DS . 'cases' . DS . 'controllers' . DS . 'preferences_controller.test.php');
		TestManager::addTestFile($this, TESTS . DS . 'cases' . DS . 'controllers' . DS . 'projects_controller.test.php');
		TestManager::addTestFile($this, TESTS . DS . 'cases' . DS . 'controllers' . DS . 'search_controller.test.php');
		TestManager::addTestFile($this, TESTS . DS . 'cases' . DS . 'controllers' . DS . 'setting_controller.test.php');
		TestManager::addTestFile($this, TESTS . DS . 'cases' . DS . 'controllers' . DS . 'types_controller.test.php');
		TestManager::addTestFile($this, TESTS . DS . 'cases' . DS . 'controllers' . DS . 'urls_controller.test.php');
		TestManager::addTestFile($this, TESTS . DS . 'cases' . DS . 'controllers' . DS . 'users_controller.test.php');
	}
}
?>
