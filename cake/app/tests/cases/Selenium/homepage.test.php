<?php
require_once 'Selenium.php';

class SeleniumTest extends UnitTestCase{

    public function setUp()
    {
        $this->selenium = new Testing_Selenium("*firefox", "http://www.ethomason.selican.dyndns.org");
        $this->selenium->start();
    }

    public function tearDown()
    {
        $this->selenium->stop();
    }

    public function testHomePage()
    {
        $this->selenium->open('/users/login');
        $this->selenium->waitForPageToLoad(10000);
	sleep(3);
        $this->assertFalse($this->selenium->isTextPresent('Error'));
        $this->assertTrue($this->selenium->isTextPresent('Username'));
  }

}


?>
