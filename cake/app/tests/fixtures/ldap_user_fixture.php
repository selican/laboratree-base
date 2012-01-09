<?php 
/* SVN FILE: $Id$ */
/* LdapUser Fixture generated on: 2010-12-20 14:53:11 : 1292856791*/

class LdapUserFixture extends CakeTestFixture {
	var $name = 'LdapUser';
	var $table = 'ldap_users';

	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);
	var $records = array(array(
		'id'  => 1,
	));
}
?>
