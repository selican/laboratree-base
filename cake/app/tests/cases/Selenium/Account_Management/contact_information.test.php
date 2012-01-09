<?php
require_once 'Selenium.php';

class ContactInfo extends UnitTestCase{

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
	        sleep(2);                /* Change Password Valid */
	
		$this->selenium->open('/users/account');
		$this->selenium->click('link=Add Address Entry');
		$this->selenium->type('//input[contains(@class, "ext-mb-input")]','2584 West Testing Drive');
		$this->selenium->click('//button[contains(text(), "OK")]');
		$this->selenium->type('//input[contains(@name, "[address1]")]','2584 West Testing Drive');
		$this->selenium->type('//input[contains(@name, "[city]")]','Fortville');
		$this->selenium->type('//input[contains(@name, "[state]")]','IN');
		$this->selenium->type('//input[contains(@name, "[zip_code]")]','46040');
		$this->selenium->type('//input[contains(@name, "[country]")]','USA');
		sleep(1);
		$this->selenium->click('//button[contains(text(), "Save")]');
		$this->selenium->waitForPageToLoad('30000');
		$this->assertTrue($this->selenium->isTextPresent('User Information Updated'));
		$this->assertFalse($this->selenium->isTextPresent('Invalid'));
		$this->assertFalse($this->selenium->isTextPresent('Error'));

		$this->selenium->open('/users/account');
		sleep(1);
		$this->selenium->waitForPageToLoad('30000');
		$this->selenium->click('//li[2]/a[2]/em/span/span');
		sleep(1);
		$this->selenium->click('link=Add Phone Number Entry');
		sleep(1);
		$this->selenium->type('//input[contains(@class, "ext-mb-input")]','Cell');
		sleep(1);
		$this->selenium->click('//button[contains(text(), "OK")]');
		sleep(1);
		$this->selenium->type('//input[contains(@name, "[phone_number]")]','3173794628');
		sleep(1);
		$this->selenium->waitForPageToLoad('30000');
		$this->selenium->click('//button[contains(text(), "Save")]');
		sleep(1);
		$this->selenium->waitForPageToLoad('30000');
		$this->assertTrue($this->selenium->isTextPresent('User Information Updated'));

		$this->selenium->open('/users/account');
		$this->selenium->waitForPageToLoad('30000');
		$this->selenium->click('//li[2]/a[2]/em/span/span');
		$this->selenium->click('link=Add Website Entry');
		$this->selenium->type('//input[contains(@class, "ext-mb-input")]','Personal website');
		$this->selenium->click('//button[contains(text(), "OK")]');
		$this->selenium->type('//input[contains(@name, "[link]")]','http://www.google.com');
		sleep(1);
		$this->selenium->waitForPageToLoad('30000');
		$this->selenium->click('//button[contains(text(), "Save")]');
		$this->selenium->waitForPageToLoad('30000');
		$this->assertTrue($this->selenium->isTextPresent('User Information Updated'));
	}
}
?>
