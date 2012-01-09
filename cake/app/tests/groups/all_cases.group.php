<?php
class AllCasesGroupTest extends GroupTest {
	var $label = 'All test cases';

	function AllCasesGroupTest() {
		$cases = $this->getCases(TESTS . DS . 'cases');

		foreach($cases as $case)
		{
			TestManager::addTestFile($this, $case);
		}
	}

	function getCases($directory)
	{
		$cases = array();
		if(is_dir($directory))
		{
			if($dh = opendir($directory))
			{
				while(($file = readdir($dh)) !== false)
				{
					if($file == '.' || $file == '..')
					{
						continue;
					}

					if(is_dir($directory . '/' . $file))
					{
						$cases = array_merge($cases, $this->getCases($directory . '/' . $file));
					}
					else if(preg_match('/\.test\.php$/', $file))
					{
						$cases[] = $directory . '/' . $file;
					}
				}
				closedir($dh);
			}
		}

		return $cases;
	}
}
?>
