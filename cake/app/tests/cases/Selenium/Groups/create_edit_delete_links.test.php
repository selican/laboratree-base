<?php
require_once 'Selenium.php';

class CreateEditDeleteLinks extends UnitTestCase{

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

                $this->selenium->type('UserUsername','selenium1');
                $this->selenium->type('UserPasswd','11111111');
                $this->selenium->click('//input[@value="Login"]');
                $this->selenium->open('/groups/dashboard/102'); //Open Selenium Testing Group
                $this->assertTrue($this->selenium->isTextPresent('Selenium Testing'));

		/* Open Group */

		$this->selenium->waitForPageToLoad('30000');
		sleep(2);	
		$this->selenium->click('link=Profile');
		$this->selenium->waitForPageToLoad('30000');		
                sleep(2);
		$this->assertTrue($this->selenium->isTextPresent('Group created for Selenium'));
		$this->selenium->waitForPageToLoad('30000');
		$this->selenium->open('/groups/dashboard/102');
		$this->selenium->click('link=- add link -');
		$this->selenium->waitForPageToLoad('30000');
	
		/* Make sure text validation works */
	
		$this->selenium->click('//button[contains(text(), "Add Link")]');
		$this->assertTrue($this->selenium->isTextPresent('Label'));

		/* Add link to Google */
		$this->selenium->type('LinkLabel','Test Link');
		$this->selenium->type('LinkLink','http://www.google.com');
		$this->selenium->type('LinkDescription','A link to Google!');
		$this->selenium->click('//button[contains(text(), "Add Link")]');
		$this->selenium->waitForPageToLoad('30000');
		sleep(2);		
		$this->selenium->click('link=exact:http://www.google.com/');

		/* Load google.com */

		$this->selenium->waitForPageToLoad('30000');
		sleep(2);
		$this->assertTrue($this->selenium->isTextPresent('Advanced'));
	
		/* Go back, make sure link is on links page */

		$this->selenium->open('/urls/group/102');
		$this->selenium->waitForPageToLoad('30000');		

		sleep(2);

		$this->assertTrue($this->selenium->isTextPresent('Google'));

		/* Change link to Yahoo */
		$this->selenium->open('/urls/group/102');
		$this->selenium->waitForPageToLoad('30000');
		sleep(2);		
		$this->selenium->click('link=Edit');
		$this->selenium->waitForPageToLoad('30000');		
		sleep(2);
		$this->selenium->type('LinkLink','http://www.yahoo.com/');
		$this->selenium->type('LinkLabel','Test Link yahoo');
		$this->selenium->type('LinkDescription','A link to Yahoo!');
		$this->selenium->click('//button[contains(text(), "Edit Link")]');
		$this->selenium->waitForPageToLoad('30000');	
		sleep(2);
		$this->selenium->click('link=exact:http://www.yahoo.com/');

		/* Load yahoo.com */
		$this->selenium->waitForPageToLoad('30000');
		
		sleep(2);

		$this->assertTrue($this->selenium->isTextPresent('Yahoo'));

		/* Go back, make sure link is on links page */
	
		$this->selenium->open('/urls/group/102');
		$this->selenium->waitForPageToLoad('30000');
                sleep(2);
	
		$this->assertTrue($this->selenium->isTextPresent('Yahoo'));

		/* Delete Link */

		$this->selenium->open('/urls/group/102');
		$this->selenium->waitForPageToLoad('30000');
		sleep(2);		
		$this->selenium->click('link=Delete');
		$this->selenium->answerOnNextPrompt('Yes');

		/* Make sure link is deleted from links list */
		$this->selenium->waitForPageToLoad('30000');
		sleep(2);
		$this->selenium->open('urls/group/102');
		$this->selenium->waitForPageToLoad('30000');
		sleep(2);		
		$this->assertFalse($this->selenium->isTextPresent('yahoo'));

		}
}
?>
