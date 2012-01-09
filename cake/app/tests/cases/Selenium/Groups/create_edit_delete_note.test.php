<?php
require_once 'Selenium.php';

class CreateEditDeleteNote extends UnitTestCase{

        public function setUp()
        {
        $this->selenium = new Testing_Selenium("*firefox", "http://ethomason.selican.dyndns.org");
        $this->selenium->start();
        }

        public function tearDown()
        {
        $this->selenium->stop();
        }

        public function test()
        {
                /* Open Login screen */

                $this->selenium->open('/users/login');
                $this->selenium->waitForPageToLoad(10000);
                $this->assertFalse($this->selenium->isTextPresent('Error'));
                $this->assertTrue($this->selenium->isTextPresent('Username'));

                /* Enter Credentials */

                $this->selenium->type('UserUsername','ethanthomason');
                $this->selenium->type('UserPasswd','testing123');
                $this->selenium->click('//input[@value="Login"]');
                $this->selenium->open('/groups/dashboard/102'); //Open Selenium Testing Group
                $this->assertTrue($this->selenium->isTextPresent('Selenium Testing'));

		/* Add Note */

		$this->selenium->click('link=- add note -');
		$this->selenium->waitForPageToLoad('30000');
		$this->selenium->type('NoteTitle','Test Note');
		$this->selenium->click('//button[contains(text(), "Save")]');
		$this->selenium->waitForPageToLoad('30000');
		$this->selenium->click('link=Notes List');
                $this->selenium->waitForPageToLoad('30000');
		sleep(2);		
		$this->assertTrue($this->selenium->isTextPresent('Test Note'));		
	
		/* Edit Note */

		$this->selenium->click('link=Edit');
		$this->selenium->waitForPageToLoad('30000');
                $this->selenium->type('NoteTitle','Edited Note');		
		$this->selenium->click('//button[contains(text(), "Save")]');
		$this->selenium->waitForPageToLoad('30000');
		sleep(2);
		$this->assertFalse($this->selenium->isTextPresent('Test Note'));
		$this->assertTrue($this->selenium->isTextPresent('Edited Note'));

		
		/* Delete Note */	

		$this->selenium->click('link=Notes List');
		$this->selenium->waitForPageToLoad('30000');		
		sleep(2);
		$this->selenium->click('link=Delete');
                $this->selenium->waitForPageToLoad('30000');
		sleep(2);
		$this->assertTrue($this->selenium->isTextPresent('Home'));
		$this->assertFalse($this->selenium->isTextPresent('Test Note'));
                $this->assertFalse($this->selenium->isTextPresent('Edited Note'));
	}
}
?>
