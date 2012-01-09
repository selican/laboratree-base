<?php
require_once 'Selenium.php';

class AdditionalInformation extends UnitTestCase{

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
                sleep(2);
                $this->assertFalse($this->selenium->isTextPresent('Error'));
                $this->assertTrue($this->selenium->isTextPresent('Username'));

                /* Enter Credentials */

                $this->selenium->type('UserUsername','selenium1');
	        sleep(1);
                $this->selenium->type('UserPasswd','11111111');
	        sleep(1);
                $this->selenium->click('//input[@value="Login"]');
                $this->selenium->waitForPageToLoad('30000');
	        sleep(2);               

		/* Modify Education */
	
		$this->selenium->open('/users/account');
		$this->selenium->waitForPageToLoad('30000');
		$this->selenium->click('link=Add Education Entry');
		$this->selenium->waitForPageToLoad('30000');
		$this->selenium->type('//input[contains(@class, "ext-mb-input")]','School');
		$this->selenium->click('//button[contains(text(), "OK")]');
		$this->selenium->type('//input[contains(@name, "[institution]")]','Some school');
		$this->selenium->type('//input[contains(@name, :[degree]")]','gen ed');
		$this->selenium->type('//input[contains(@name, "[years]")]','13');
		$this->selenium->click('//button[contains(text(), "Save")]');
		$this->selenium->waitForPageToLoad('30000');
		$this->assertTrue($this->selenium->isTextPresent('User Information Updated'));
		$this->assertFalse($this->selenium->isTextPresent('Invalid'));
		$this->assertFalse($this->selenium->isTextPresent('Error'));

		/* Modify Associations */

		$this->selenium->open('/users/account');
		$this->selenium->waitForPageToLoad('30000');
		$this->selenium->click('link=Add Association Entry');
		$this->selenium->waitForPageToLoad('30000');
		$this->selenium->type('//input[contains(@class, "ext-mb-input")]','Association');
		$this->selenium->click('//button[contains(text(), "OK")]');
		sleep(1);
		$this->selenium->type('//input[contains(@name, "[association]")]','NRA');
		$this->selenium->type('//input[contains(@name, "[role]")]','big guns');
		sleep(1);
		$this->selenium->click('//button[contains(text(), "Save")]');
		$this->selenium->waitForPageToLoad('30000');
		sleep(1);
		$this->assertTrue($this->selenium->isTextPresent('User Information Updated'));
		$this->assertFalse($this->selenium->isTextPresent('Invalid'));
		$this->assertFalse($this->selenium->isTextPresent('Error'));

		/* Modify Awards */

		$this->selenium->open('/users/account');
		$this->selenium->waitForPageToLoad('30000');
		$this->selenium->click('link=Add Award Entry');
		$this->selenium->waitForPageToLoad('30000');
		$this->selenium->type('//input[contains(@class, "ext-mb-input")]','Award');
		$this->selenium->click('//button[contains(text(), "OK")]');
		sleep(1);
		$this->selenium->type('//input[contains(@name, "[award]")]','Eagle Scout');
		sleep(1);
		$this->selenium->click('//button[contains(text(), "Save")]');
		$this->selenium->waitForPageToLoad('30000');
		$this->assertTrue($this->selenium->isTextPresent('User Information Updated'));
		$this->assertFalse($this->selenium->isTextPresent('Invalid'));
		$this->assertFalse($this->selenium->isTextPresent('Error'));

		/* Modify Interests */

		sleep(1);
		$this->selenium->open('/users/account');
		$this->selenium->waitForPageToLoad('30000');
		sleep(6);
		$this->selenium->type('UserInterests','no interests as of right now');
		sleep(6);
		$this->selenium->click('//button[contains(text(), "Save")]');
		$this->selenium->waitForPageToLoad('30000');
		sleep(5);
		$this->assertTrue($this->selenium->isTextPresent('User Information Updated'));
		$this->assertFalse($this->selenium->isTextPresent('Invalid'));
		$this->assertFalse($this->selenium->isTextPresent('Error'));

		sleep(1);
		$this->selenium->click('link=Selenium Tester');
		$this->selenium->waitForPageToLoad('30000');
		sleep(5);
		$this->assertTrue($this->selenium->isTextPresent('no interests as of right now'));

	}
}
?>
