<?php 
/* SVN FILE: $Id$ */
/* Application Fixture generated on: 2010-12-20 14:53:36 : 1292856816*/

class ApplicationFixture extends CakeTestFixture {
	var $name = 'Application';
	var $table = 'applications';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'title' => array('type'=>'string', 'null' => false, 'default' => NULL, 'length' => 128),
		'titleUrl' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'directory_title' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'description' => array('type'=>'text', 'null' => true, 'default' => NULL),
		'url' => array('type'=>'string', 'null' => false, 'default' => NULL, 'length' => 128),
		'screenshot' => array('type'=>'string', 'null' => true, 'default' => NULL, 'length' => 128),
		'thumbnail' => array('type'=>'string', 'null' => true, 'default' => NULL, 'length' => 128),
		'author' => array('type'=>'string', 'null' => true, 'default' => NULL, 'length' => 128),
		'author_email' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'author_affliation' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'author_location' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'author_photo' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'author_aboutme' => array('type'=>'text', 'null' => true, 'default' => NULL),
		'author_quote' => array('type'=>'text', 'null' => true, 'default' => NULL),
		'author_link' => array('type'=>'string', 'null' => true, 'default' => NULL),
		'settings' => array('type'=>'text', 'null' => true, 'default' => NULL),
		'version' => array('type'=>'string', 'null' => true, 'default' => NULL, 'length' => 64),
		'height' => array('type'=>'integer', 'null' => true, 'default' => NULL),
		'width' => array('type'=>'integer', 'null' => true, 'default' => NULL),
		'scrolling' => array('type'=>'boolean', 'null' => false, 'default' => NULL),
		'scaling' => array('type'=>'boolean', 'null' => false, 'default' => NULL),
		'show_in_directory' => array('type'=>'boolean', 'null' => false, 'default' => '1'),
		'show_stats' => array('type'=>'boolean', 'null' => false, 'default' => '1'),
		'icons' => array('type'=>'text', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);
	var $records = array(array(
		'id'  => 1,
		'title'  => 'Test Application',
		'titleUrl'  => 'http://test.example.com',
		'directory_title'  => 'Tests',
		'description'  => 'Test Application',
		'url'  => 'http://test.example.com',
		'screenshot'  => 'http://test.example.com/screenshot.jpg',
		'thumbnail'  => 'http://test.example.com/thumbnail.jpg',
		'author'  => 'Test Author',
		'author_email'  => 'test@example.com',
		'author_affliation'  => 'Test University',
		'author_location'  => 'Test Location',
		'author_photo'  => 'http://test.example.com/author.jpg',
		'author_aboutme'  => 'Test Author',
		'author_quote'  => 'Test Test',
		'author_link'  => 'http://test.example.com/author',
		'settings'  => '',
		'version'  => '',
		'height'  => 100,
		'width'  => 100,
		'scrolling'  => 1,
		'scaling'  => 1,
		'show_in_directory'  => 1,
		'show_stats'  => 1,
		'icons'  => 'AAAAA'
	),

	array(
		'id'  => 2,
                'title'  => 'Test',
                'titleUrl'  => null,
                'directory_title'  => null,
                'description'  => 'Test',
                'url'  => 'http://apps.selican.dyndns.org/test/test.xml',
                'screenshot'  => 'http://example.com',
                'thumbnail'  => 'http://example.com',
                'author'  => 'Test Author',
                'author_email'  => 'test@example.com',
                'author_affliation'  => null,
                'author_location'  => null,
                'author_photo'  => null,
                'author_aboutme'  => null,
                'author_quote'  => null,
                'author_link'  => null,
                'settings'  => '[]',
                'version'  => null,
                'height'  => 150,
                'width'  => null,
                'scrolling'  => 0,
                'scaling'  => 0,
                'show_in_directory'  => 1,
                'show_stats'  => 1,
                'icons'  => null
        ),);
}
?>
