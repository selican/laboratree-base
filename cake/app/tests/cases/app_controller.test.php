<?php 
App::import('Controller', 'App');

class AppControllerTestController extends AppController {
	var $name = 'Test';
	var $uses = array();
	var $autoRender = false;
}

class AppControllerTest extends CakeTestCase {
	var $App = null;
	var $fixtures = array('app.helps', 'app.app_category', 'app.app_data', 'app.application', 'app.app_module', 'app.attachment', 'app.discussion', 'app.doc', 'app.docs_permission', 'app.docs_tag', 'app.docs_type_data', 'app.docs_type_field', 'app.docs_type', 'app.docs_type_row', 'app.docs_version', 'app.group', 'app.groups_address', 'app.groups_association', 'app.groups_award', 'app.groups_interest', 'app.groups_phone', 'app.groups_projects', 'app.groups_publication', 'app.groups_setting', 'app.groups_url', 'app.groups_users', 'app.inbox', 'app.inbox_hash', 'app.interest', 'app.message_archive', 'app.message', 'app.note', 'app.ontology_concept', 'app.preference', 'app.project', 'app.projects_association', 'app.projects_interest', 'app.projects_setting', 'app.projects_url', 'app.projects_users', 'app.role', 'app.setting', 'app.site_role', 'app.tag', 'app.type', 'app.url', 'app.user', 'app.users_address', 'app.users_association', 'app.users_award', 'app.users_education', 'app.users_interest', 'app.users_job', 'app.users_phone', 'app.users_preference', 'app.users_publication', 'app.users_url');

	function startTest() {
		$this->App = new AppControllerTestController();
		$this->App->constructClasses();
		$this->App->Session->write('Auth.User', array(
			'id' => 1,
			'username' => 'testuser',
			'changepass' => 0,
		));
	}

	function testAppControllerInstance() {
		$this->assertTrue(is_a($this->App, 'AppController'));
	}

	function testBeforeFilter() {

		$this->App->beforeFilter();
		$this->App->params['controller'] = array('controller' => 'Settings');
		$this->App->params['action'] = array('action' => 'do something');
		$this->App->params['pass'] = true;

		$this->App->Session->write('Auth.User', array(
                        'id' => 1,
                        'username' => 'testuser',
			'changepass' => 0,
                ));
	}

	function testGeocode()
	{
		$address = '212 West 10th St. Indianapolis, IN 46202';
		
		$results = $this->App->geocode($address);
		$expected = array('39.7813090', '-86.1533800');

		$this->assertEqual($results, $expected);
	}

	function testGeocodeExtended()
	{
		$address = '212 West 10th St., Suite A-470, Indianapolis, IN 46202';

		$results = $this->App->geocode($address);
		$expected = array('39.7813090', '-86.1533800');

		$this->assertEqual($results, $expected);
	}

	function testGeocodeNullAddress(){
		$results = $this->App->geocode(null);

		$expected = array(0,0);

		$this->assertEqual($results, $expected);
	}

	function testGeocodeInvalidAddress()
	{
		$address = '!';

		$results = $this->App->geocode($address);
		$expected = array(0,0);

		$this->assertEqual($results, $expected);
	}
/*
	function testEmail() {
		$this->App->email(null);
	}

	function testEmailVerification() {
		$this->App->email_verification(null);
	}

	function testEmailChange() {
		$this->App->email_change(null);
	}

	function testEmailPasswordReset() {

	}

	function testEmailPasswordRequest() {

	}

	function testEmailMessage() {

	}

*/
	function testGetRoleNullUserId() {
		$results = $this->App->get_role('user', 1, null);
	        $expected = 'user.manager';

                $this->assertEqual($results, $expected);
	}

	function testGetRoleStringUserId() {
                $results = $this->App->get_role('user', 1, 'string');
	        $expected = 'user';

                $this->assertEqual($results, $expected);
	}

	function testGetRoleBoolUserId() {
        	$results = $this->App->get_role('user', 1, true);
	        $expected = 'user';

                $this->assertEqual($results, $expected);
	}

	function testGetRoleNegativeUserId() {
                $results = $this->App->get_role('user', 1, -1);
	        $expected = 'user';

                $this->assertEqual($results, $expected);
	}

	function testGetRoleUserManager()
	{
		$table_type = 'user';
		$table_id = 1;
		$user_id = 1;

		$results = $this->App->get_role($table_type, $table_id, $user_id);
		$expected = 'user.manager';
		
		$this->assertEqual($results, $expected);
	}

	function testGetRoleUserColleague()
	{
		$table_type = 'user';
		$table_id = 1;
		$user_id = 2;

		$results = $this->App->get_role($table_type, $table_id, $user_id);
		$expected = 'user.colleague';
		
		$this->assertEqual($results, $expected);
	}

	function testGetRoleUser()
	{
		$table_type = 'user';
		$table_id = 1;
		$user_id = 9000;

		$results = $this->App->get_role($table_type, $table_id, $user_id);
		$expected = 'user';
		
		$this->assertEqual($results, $expected);
	}

	function testGetRoleGroupManager()
	{
		$table_type = 'group';
		$table_id = 1;
		$user_id = 1;

		$results = $this->App->get_role($table_type, $table_id, $user_id);
		$expected = 'group.manager';
		
		$this->assertEqual($results, $expected);
	}

	function testGetRoleGroupMember()
	{
		$table_type = 'group';
		$table_id = 1;
		$user_id = 2;

		$results = $this->App->get_role($table_type, $table_id, $user_id);
		$expected = 'group.member';
		
		$this->assertEqual($results, $expected);
	}

	function testGetRoleProjectManager()
	{
		$table_type = 'project';
		$table_id = 1;
		$user_id = 1;

		$results = $this->App->get_role($table_type, $table_id, $user_id);
		$expected = 'project.manager';
		
		$this->assertEqual($results, $expected);
	}

	function testGetRoleProjectMember()
	{
		$table_type = 'project';
		$table_id = 1;
		$user_id = 2;

		$results = $this->App->get_role($table_type, $table_id, $user_id);
		$expected = 'project.member';
		
		$this->assertEqual($results, $expected);
	}

	function testGetRoleNullTableType()
	{
		$table_type = null;
		$table_id = 1;
		$user_id = 1;

		$results = $this->App->get_role($table_type, $table_id, $user_id);
		$expected = 'user';
		
		$this->assertEqual($results, $expected);
	}

	function testGetRoleInvalidTableType()
	{
		$table_type = 'invalid';
		$table_id = 1;
		$user_id = 1;

		$results = $this->App->get_role($table_type, $table_id, $user_id);
		$expected = 'user';
		
		$this->assertEqual($results, $expected);
	}

	function testGetRoleNullTableId()
	{
		$table_type = 'user';
		$table_id = null;
		$user_id = 1;

		$results = $this->App->get_role($table_type, $table_id, $user_id);
		$expected = 'user';
		
		$this->assertEqual($results, $expected);
	}

	function testGetRoleInvalidTableId()
	{
		$table_type = 'user';
		$table_id = 'invalid';
		$user_id = 1;

		$results = $this->App->get_role($table_type, $table_id, $user_id);
		$expected = 'user';
		
		$this->assertEqual($results, $expected);
	}

	function testGetUserRoleEmptyOtherId() {
                $results = $this->App->get_user_role(1, null);
                $expected = 'user.manager';

                $this->assertEqual($results, $expected);
	}

        function testGetUserRoleEmptyOtherIdReturnUser() {
		$this->App->Session->write('Auth.User', array(null));

		$results = $this->App->get_user_role(1, null);
                $expected = 'user';

                $this->assertEqual($results, $expected);
        }

	function testGetUserRoleBoolOtherId() {
                $results = $this->App->get_user_role(1, true);
                $expected = 'user';

                $this->assertEqual($results, $expected);
	}

	function testGetUserRoleStringOtherId() {
                $results = $this->App->get_user_role(1, 'string');
                $expected = 'user';

                $this->assertEqual($results, $expected);
	}

	function testGetUserRoleNegativeOtherId() {
                $results = $this->App->get_user_role(1, -1);
                $expected = 'user';

                $this->assertEqual($results, $expected);
	}

	function testGetUserRoleUserManager()
	{
		$user_id = 1;
		$other_id = 1;

		$results = $this->App->get_user_role($user_id, $other_id);
		$expected = 'user.manager';

		$this->assertEqual($results, $expected);
	}

	function testGetUserRoleUserColleague()
	{
		$user_id = 1;
		$other_id = 2;

		$results = $this->App->get_user_role($user_id, $other_id);
		$expected = 'user.colleague';

		$this->assertEqual($results, $expected);
	}

	function testGetUserRoleUser()
	{
		$user_id = 1;
		$other_id = 9000;

		$results = $this->App->get_user_role($user_id, $other_id);
		$expected = 'user';

		$this->assertEqual($results, $expected);
	}

	function testGetUserRoleNullUserId()
	{
		$user_id = null;
		$other_id = 1;

		$results = $this->App->get_user_role($user_id, $other_id);
		$expected = 'user';
		
		$this->assertEqual($results, $expected);
	}

	function testGetUserRoleInvalidUserId()
	{
		$user_id = 'invalid';
		$other_id = 1;

		$results = $this->App->get_user_role($user_id, $other_id);
		$expected = 'user';
		
		$this->assertEqual($results, $expected);
	}

	function testGetGroupRoleBoolUserId() {
                $results = $this->App->get_group_role(1, true);
                $expected = 'user';

                $this->assertEqual($results, $expected);
	}

	function testGetGroupRoleStringUserId() {
                $results = $this->App->get_group_role(1, 'string');
                $expected = 'user';

                $this->assertEqual($results, $expected);
	}

	function testGetGroupRoleNegativeUserId() {
                $results = $this->App->get_group_role(1, -2);
                $expected = 'user';

                $this->assertEqual($results, $expected);
	}

	function testGetGroupRoleEmptyUserIdReturnUser() {
		$this->App->Session->write('Auth.User', array(null));

		$results = $this->App->get_group_role(1, null);
                $expected = 'user';

                $this->assertEqual($results, $expected);
	}

	function testGetGroupRoleEmptyUser() {
                $results = $this->App->get_group_role(1, null);
                $expected = 'group.manager';

                $this->assertEqual($results, $expected);
	}

	function testGetGroupRoleGroupManager()
	{
		$group_id = 1;
		$user_id = 1;

		$results = $this->App->get_group_role($group_id, $user_id);
		$expected = 'group.manager';

		$this->assertEqual($results, $expected);
	}

	function testGetGroupRoleGroupMember()
	{
		$group_id = 1;
		$user_id = 2;

		$results = $this->App->get_group_role($group_id, $user_id);
		$expected = 'group.member';

		$this->assertEqual($results, $expected);
	}

	function testGetGroupRoleGroup()
	{
		$group_id = 1;
		$user_id = 9000;

		$results = $this->App->get_group_role($group_id, $user_id);
		$expected = 'user';

		$this->assertEqual($results, $expected);
	}

	function testGetGroupRoleNullGroupId()
	{
		$group_id = null;
		$user_id = 1;

		$results = $this->App->get_group_role($group_id, $user_id);
		$expected = 'user';
		
		$this->assertEqual($results, $expected);
	}

	function testGetGroupRoleInvalidGroupId()
	{
		$group_id = 'invalid';
		$user_id = 1;

		$results = $this->App->get_group_role($group_id, $user_id);
		$expected = 'user';
		
		$this->assertEqual($results, $expected);
	}

	function testGetProjectRoleBoolUserId() {
	        $results = $this->App->get_project_role(1, true);
                $expected = 'user';

                $this->assertEqual($results, $expected);
	}

	function testGetProjectRoleStringUserId() {
	        $results = $this->App->get_project_role(1, 'string');
                $expected = 'user';

                $this->assertEqual($results, $expected);
	}

	function testGetProjectRoleNegativeUserId() {
	        $results = $this->App->get_project_role(1, -1);
                $expected = 'user';

                $this->assertEqual($results, $expected);
	}

	function testGetProjectRoleEmptyUserIdReturnUser() {
		$this->App->Session->write('Auth.User', array(null));
	        $results = $this->App->get_project_role(1, null);
                $expected = 'user';

                $this->assertEqual($results, $expected);
	}

	function testGetProjectRoleEmptyUserId() {
	        $results = $this->App->get_project_role(1, null);
                $expected = 'project.manager';

                $this->assertEqual($results, $expected);
	}	
	
	function testGetProjectRoleProjectManager()
	{
		$project_id = 1;
		$user_id = 1;

		$results = $this->App->get_project_role($project_id, $user_id);
		$expected = 'project.manager';

		$this->assertEqual($results, $expected);
	}

	function testGetProjectRoleProjectMember()
	{
		$project_id = 1;
		$user_id = 2;

		$results = $this->App->get_project_role($project_id, $user_id);
		$expected = 'project.member';

		$this->assertEqual($results, $expected);
	}

	function testGetProjectRoleProject()
	{
		$project_id = 1;
		$user_id = 9000;

		$results = $this->App->get_project_role($project_id, $user_id);
		$expected = 'user';

		$this->assertEqual($results, $expected);
	}

	function testGetProjectRoleNullProjectId()
	{
		$project_id = null;
		$user_id = 1;

		$results = $this->App->get_project_role($project_id, $user_id);
		$expected = 'user';
		
		$this->assertEqual($results, $expected);
	}

	function testGetProjectRoleInvalidProjectId()
	{
		$project_id = 'invalid';
		$user_id = 1;

		$results = $this->App->get_project_role($project_id, $user_id);
		$expected = 'user';
		
		$this->assertEqual($results, $expected);
	}
/*
	function testforceSSL() {
		$this->App->forceSSL();
	}
*/
	function testAccessValidNull()
	{
		$table_type = 'user';
		$table_id = 2;

		$this->assertTrue($this->App->access($table_type, $table_id));
	}	

	function testAccessValidString()
	{
		$table_type = 'user';
		$table_id = 2;
		$allowed = 'user.colleague';

		$this->assertTrue($this->App->access($table_type, $table_id, $allowed));
	}

	function testAccessValidArrayString()
	{
		$table_type = 'user';
		$table_id = 2;
		$allowed = array(
			'user' => 'user.colleague',
		);

		$this->assertTrue($this->App->access($table_type, $table_id, $allowed));
	}

	function testAccessValidArrayArray()
	{
		$table_type = 'user';
		$table_id = 2;
		$allowed = array(
			'user' => array(
				'user.manager',
				'user.colleague',
			),
		);

		$this->assertTrue($this->App->access($table_type, $table_id, $allowed));
	}

	function testAccessInvalidTableType()
	{
		$table_type = 'invalid';
		$table_id = 1;

		$this->assertFalse($this->App->access($table_type, $table_id));
	}

	function testAccessInvalidTableId()
	{
		$table_type = 'user';
		$table_id = 'invalid';

		$this->assertFalse($this->App->access($table_type, $table_id));
	}

	function testAccessInvalidAllowedString() {
		$table_type = 'user';
		$table_id = 2;
		$allowed = 'invalid';

		$this->assertFalse($this->App->access($table_type, $table_id, $allowed));
	}

	function testAccessInvalidAllowedArrayType() {
		$table_type = 'user';
		$table_id = 2;
		$allowed = array(
			'invalid' => array(
				'user.colleague',
			),
		);

		$this->assertTrue($this->App->access($table_type, $table_id, $allowed));
	}

	function testAccessInvalidAllowedArrayString() {
		$table_type = 'user';
		$table_id = 2;
		$allowed = array(
			'user' => array(
				'invalid',
			),
		);

		$this->assertFalse($this->App->access($table_type, $table_id, $allowed));
	}

	function testAccessInvalidAllowedArrayArray() {
		$table_type = 'user';
		$table_id = 2;
		$allowed = array(
			'user' => array('invalid'),
		);

		$this->assertFalse($this->App->access($table_type, $table_id, $allowed));
	}

	function testAccessInvalidAllowedRole() {
		$table_type = 'user';	
		$table_id = 2;
		$allowed = array(
			'user' => array(
				'user.manager',
			),
		);

		$this->assertFalse($this->App->access($table_type, $table_id, $allowed));
	}

	function testIsAdminTrue()
	{
		$this->App->Session->write('Auth.User', array(
			'id' => 1,
			'username' => 'brandon',
			'admin' => 1,
		));

		$this->assertTrue($this->App->isAdmin());
	}

	function testIsAdminFalse()
	{
		$this->App->Session->write('Auth.User', array(
			'id' => 1,
			'username' => 'brandon',
			'admin' => 0,
		));

		$this->assertFalse($this->App->isAdmin());
	}

	function testIsAdminNullAdmin()
	{
		$this->App->Session->write('Auth.User', array(
			'id' => 1,
			'username' => 'brandon',
			'admin' => null,
		));

		$this->assertFalse($this->App->isAdmin());
	}

	function testIsAdminInvalidAdmin()
	{
		$this->App->Session->write('Auth.User', array(
			'id' => 1,
			'username' => 'brandon',
			'admin' => 'invalid',
		));

		$this->assertFalse($this->App->isAdmin());
	}

	function testFullUrlShort()
	{
		$url = 'example.com';
		$results = $this->App->fullurl($url);
		$expected = 'http://example.com/';

		$this->assertEqual($results, $expected);
	}

	function testFullUrlLong()
	{
		$url = 'www.example.com';
		$results = $this->App->fullurl($url);
		$expected = 'http://www.example.com/';

		$this->assertEqual($results, $expected);
	}

	function testFullUrlComplex()
	{
		$url = 'example.com/complex/url';
		$results = $this->App->fullurl($url);
		$expected = 'http://example.com/complex/url';

		$this->assertEqual($results, $expected);
	}

	function testFullUrlComplete()
	{
		$url = 'http://example.com';
		$results = $this->App->fullurl($url);
		$expected = 'http://example.com/';

		$this->assertEqual($results, $expected);
	}

	function testFullUrlExtension()
	{
		$url = 'http://example.com/index.html';
		$results = $this->App->fullurl($url);
		$expected = 'http://example.com/index.html';

		$this->assertEqual($results, $expected);
	}

	function testFullUrlTilda()
	{
		$url = 'http://example.com/~user';
		$results = $this->App->fullurl($url);
		$expected = 'http://example.com/~user';

		$this->assertEqual($results, $expected);
	}

	function testFullUrlNullUrl()
	{
		$url = null;
		$results = $this->App->fullurl($url);

		$this->assertFalse($results);
	}

	function testFullUrlInvalidUrl()
	{
		$url = 'invalid';
		$results = $this->App->fullurl($url);
		$expected = 'http://invalid/';

		$this->assertEqual($results, $expected);
	}

	function testGrouplist()
	{
		$results = $this->App->grouplist();
		$expected = array(
			'Groups' => array(
				'group:3' => 'Another Private Test Group',
				'group:1' => 'Private Test Group',
			),
			'Projects' => array(
				'project:3' => 'Another Private Test Project',
				'project:1' => 'Private Test Project',
			),
		);

		$this->assertEqual($results, $expected);
	}
/*	
	function testDel() {
		$this->App->del();
	}	
*/

	function endTest() {
		unset($this->App);
	}
}
?>
