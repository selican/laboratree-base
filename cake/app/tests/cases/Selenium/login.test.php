<?php
require_once 'Selenium.php';

class testLogin  extends UnitTestCase{

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
	/* Valid Login */

	$this->selenium->open('/users/login');
	$this->selenium->waitForPageToLoad('30000');
	$this->selenium->type('UserUsername','selenium1');
	$this->selenium->type('UserPasswd','11111111');
	$this->selenium->click('//input[@value="Login"]');
	$this->selenium->waitForPageToLoad('30000');
	sleep(5);
	$this->assertFalse($this->selenium->isTextPresent('InvalidArgumentException'));
	sleep(2);
	$this->selenium->click('link=Logout');
	$this->selenium->waitForPageToLoad('30000');
	sleep(2);
	
	/* No parameters */

	$this->selenium->open('/users/login');
	$this->selenium->waitForPageToLoad('30000');
	sleep(2);
	$this->selenium->click('//input[@value="Login"]');
	$this->selenium->waitForPageToLoad('30000');
	sleep(2);
	$this->assertFalse($this->selenium->isTextPresent('InvalidArgumentException'));
	$this->assertTrue($this->selenium->isTextPresent('Login failed. Invalid username or password.'));

	/* Username, no password */

	$this->selenium->open('/users/login');
	$this->selenium->waitForPageToLoad('30000');
	$this->selenium->type('UserUsername','selenium1');
	$this->selenium->click('//input[@value="Login"]');
	$this->selenium->waitForPageToLoad('30000');
	$this->assertFalse($this->selenium->isTextPresent('InvalidArgumentException'));
	$this->assertTrue($this->selenium->isTextPresent('Login failed. Invalid username or password.'));

	/* Password, no username */

	$this->selenium->open('/users/login');
	$this->selenium->waitForPageToLoad('30000');
	$this->selenium->type('UserPasswd','randompass');
	$this->selenium->click('//input[@value="Login"]');
	$this->selenium->waitForPageToLoad('30000');
	$this->assertFalse($this->selenium->isTextPresent('InvalidArgumentException'));
	$this->assertTrue($this->selenium->isTextPresent('Login failed. Invalid username or password.'));

	/* Username, invalid password */

	$this->selenium->open('/users/login');
        $this->selenium->waitForPageToLoad('30000');
	$this->selenium->type('UserUsername','selenium1');
        $this->selenium->type('UserPasswd','randompass');
        $this->selenium->click('//input[@value="Login"]');
        $this->selenium->waitForPageToLoad('30000');
        $this->assertFalse($this->selenium->isTextPresent('InvalidArgumentException'));
        $this->assertTrue($this->selenium->isTextPresent('Login failed. Invalid username or password.'));    

	}
}
?>
