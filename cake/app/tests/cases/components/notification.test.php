<?php
App::import('Controller', 'App');
App::import('Component', 'Notification');
App::import('Component', 'Pref');

Mock::generatePartial('PrefComponent', 'NotificationComponentMockPrefComponent', array('prefers'));

class NotificationComponentTestNotificationComponent extends NotificationComponent
{
	var $query_string = array();

	function initialize(&$controller, $settings = array())
	{
		parent::initialize($controller, $settings);
	}

	function startup(&$controller)
	{
		parent::startup($controller);
	}	
	

	function discussion_add($table_type, $table_id, $discussion_id, $type)
	{
		$this->parameters[] = array(
			$table_type,
			$table_id,
			$discussion_id,
			$type,
		);
		return true;
	}

}

class NotificationComponentTestController extends AppController {
        var $name = 'Test';
        var $uses = array();
        var $components = array(
                'Notification',
		'NotificationComponentTestNotification',
        );
}

class NotificationTest extends CakeTestCase
{

        var $fixtures = array('app.helps', 'app.app_category', 'app.app_data', 'app.application', 'app.app_module', 'app.attachment', 'app.digest', 'app.discussion', 'app.doc', 'app.docs_permission', 'app.docs_tag', 'app.docs_type_data', 'app.docs_type_field', 'app.docs_type', 'app.docs_type_row_data', 'app.docs_type_row', 'app.docs_version', 'app.group', 'app.groups_address', 'app.groups_association', 'app.groups_award', 'app.groups_interest', 'app.groups_phone', 'app.groups_projects', 'app.groups_publication', 'app.groups_setting', 'app.groups_url', 'app.groups_users', 'app.inbox', 'app.inbox_hash', 'app.interest', 'app.message_archive', 'app.message', 'app.note', 'app.ontology_concept', 'app.preference', 'app.project', 'app.projects_association', 'app.projects_interest', 'app.projects_setting', 'app.projects_url', 'app.projects_users', 'app.role', 'app.setting', 'app.site_role', 'app.tag', 'app.type', 'app.url', 'app.user', 'app.users_address', 'app.users_association', 'app.users_award', 'app.users_education', 'app.users_interest', 			      'app.users_job', 'app.users_phone', 'app.users_preference', 'app.users_publication', 'app.users_url', 'app.ldap_user');

        function startTest()
        {
                $this->Controller = new NotificationComponentTestController();
                $this->Controller->constructClasses();
                $this->Controller->Component->initialize($this->Controller);
		//$this->Controller->Notification = $this->Controller->NotificationComponentTestNotification;
	}

        function testStartup() {
		$this->Controller->Notification->startup($this->Controller);
	}
	
	function testNotificationInstance() {
                $this->assertTrue(is_a($this->Controller->Notification, 'NotificationComponent'));
        }

	function testDiscussionAddInvalidTableType() {
		try {
			$this->Controller->Notification->discussion_add(null, '1', '1', 'topic');
			$this->fail();
		}
	
		catch(InvalidArgumentException $e) {
			$this->pass();
		}

		try {
			$this->Controller->Notification->discussion_add('1', '1', '1', 'topic');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

		try {
			$this->Controller->Notification->discussion_add('kitten', '1', '1', 'topic');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}
	}

	function testDiscussionAddInvalidTableID() {
		try {
			$this->Controller->Notification->discussion_add('group', null, '1', 'topic');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->discussion_add('group', 'string', '1', 'topic');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->discussion_add('group', '0', '1', 'topic');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testDiscussionAddNullDiscussionID()  {
                try {
                        $this->Controller->Notification->discussion_add('group', 1, null, 'topic');
	                $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

	}

	function testDiscussionAddStringDiscussionID() {
                try {
                        $this->Controller->Notification->discussion_add('group', '1', 'string', 'topic');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testDiscussionAddZeroDiscussionID() {
                try {
                        $this->Controller->Notification->discussion_add('group', '1', '0', 'topic');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
        }

	function testDiscussionAddInvalidType()  {
                try {
                        $this->Controller->Notification->discussion_add('group', '1', '1', null);
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->discussion_add('group', '1', '1', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->discussion_add('group', '1', '1', 'kitten');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	 }

	function testDiscussionAddValidProjectTableType()  {
                try {
			$this->Controller->Notification->discussion_add('project', 1, 15, 'topic');
			$this->pass();
		}

                catch(InvalidArgumentException $e) {
                        $this->fail();
                }
	}
	
	function testDiscussionAddValidGroupTableType() {
		try {
			$this->Controller->Notification->discussion_add('group', 1, 1, 'topic');
			$this->pass();
		}

		catch(InvalidArgumentException $e) {
			$this->fail();
		}
        }
/*
	function testDiscussionAddValid2() {
			$this->Controller->Notification->discussion_add('project', '1', '16', 'topic');
		}
*/	
	function testDiscussionEditInvalidTableType() {
                try {
                        $this->Controller->Notification->discussion_edit(null, 1, 1, 'topic');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
  
                try {
                        $this->Controller->Notification->discussion_edit(1, 1, 1, 'topic');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->discussion_edit('kitten', 1, 1, 'topic');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
        }

	function testDiscussionEditInvalidTableID() {
		try {
			$this->Controller->Notification->discussion_edit('group', null, 1, 'topic');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->discussion_edit('group', 'string', 1, 'topic');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->discussion_edit('group', 0, 1, 'topic');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testDiscussionEditInvalidDiscussionID() {
		try {
			$this->Controller->Notification->discussion_edit('group', 1, null, 'topic');
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->discussion_edit('group', '1', 'string', 'topic');
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->discussion_edit('group', '1', '0', 'topic');
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

        function testDiscussionEditInvalidType()  {
                try {
                        $this->Controller->Notification->discussion_edit('group', '1', '1', null);
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
       
                try {
                        $this->Controller->Notification->discussion_edit('group', '1', '1', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->discussion_edit('group', '1', '1', 'kitten');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	 }

	function testDiscussionEditValid() {
		try {
			$this->Controller->Notification->discussion_edit('project', '1', '16', 'topic');
			$this->pass();
		}	

		catch(InvalidArgumentException $e) {
			$this->pass();
		}
	}

	function testDiscussionEditValidAgain() {
		try {
			$this->Controller->Notification->discussion_edit('group', '1', '1', 'category');
			$this->pass();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}
	}

	function testDiscussionDeleteInvalidTableType() {
		try {
			$this->Controller->Notification->discussion_delete(null, '1', 'string', 'topic');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->discussion_delete('1', '1', 'string', 'topic');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->discussion_delete('kitten', '1', 'string', 'topic');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testDiscussionDeleteInvalidTableID() {
		try {
			$this->Controller->Notification->discussion_delete('group', null, 'string', 'topic');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->discussion_delete('group', 'string', 'string', 'topic');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->discussion_delete('group', '0', 'string', 'topic');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testDiscussionDeleteInvalidDiscussion() {
		try {
			$this->Controller->Notification->discussion_delete('group', 1, null, 'topic');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->discussion_delete('group', 1, 1, 'topic');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
      		}
	}

	function testDiscussionDeleteInvalidType() {
		try {
			$this->Controller->Notification->discussion_delete('group', '1', 'discussion', null);
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->discussion_delete('group', '1', 'discussion', 'kitten');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testDiscussionDeleteValid() {
		try {
			$this->Controller->Notification->discussion_delete('group', '1', 'Ethan Editing', 'topic');
			$this->pass();
		}

		catch(InvalidArgumentException $e) {
			$this->fail();
		}

		try {
			$this->Controller->Notification->discussion_delete('project', '1', '16', 'topic');
			$this->pass();
		}

		catch(InvalidArgumentException $e) {
			$this->fail();
		}
	}	

	function testDocAddInvalidTableType() {
		try {
			$this->Controller->Notification->doc_add(null, '1', '1');		
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->doc_add('1', '1', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->doc_add('kitten', '1', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testDocAddInvalidTableID() {
		try {
			$this->Controller->Notification->doc_add('group', null, '1');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->doc_add('group', 'string', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->doc_add('group', '0', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testDocAddInvalidDocID() {
		try {
			$this->Controller->Notification->doc_add('group', '1', null);
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->doc_add('group', '1', 'string');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->doc_add('group', '1', '0');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }


	}

	function testDocAddValidGroup() {
		try {
			$this->Controller->Notification->doc_add('group', 1, 1);
			$this->pass();
		}

		catch(InvalidArgumentException $e) {
			$this->fail();
		}
	}

	function testDocAddValidProject() {
		try {
			$this->Controller->Notification->doc_add('project', 1, 15);
			$this->pass();
		}

		catch(InvalidArgumentException $e) {
			$this->fail();
		}
	}

	function testDocEditInvalidTableType() {
		try {
			$this->Controller->Notification->doc_edit(null, '1', '1');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}
                try {
                        $this->Controller->Notification->doc_edit('1', '1', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->doc_edit('kitten', '1', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testDocEditInvalidTableID() {
		try {
			$this->Controller->Notification->doc_edit('group', null, '1');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}
                try {
                        $this->Controller->Notification->doc_edit('group', 'string', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->doc_edit('group', '0', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testDocEditInvalidDocID() {
		try {
			$this->Controller->Notification->doc_edit('group', '1', null);
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->doc_edit('group', '1', 'string');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->doc_edit('group', '1', '0');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testDocEditValid() {
		try {
			$this->Controller->Notification->doc_edit('group', '1', '1');
			$this->pass();
		}

		catch(InvalidArgumentException $e) {
			$this->fail();
		}

		try {
			$this->Controller->Notification->doc_edit('project', '1', '1');
			$this->pass();
		}

		catch(InvalidArgumentException $e) {
			$this->fail();
		}
	}

	function testDocDeleteNullTableType() {
		try {
			$this->Controller->Notification->doc_delete(null, '1', 'string');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}
	}

	function testDocDeleteIntegerTableType() {
                try {
                        $this->Controller->Notification->doc_delete('1', '1', 'string');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testDocDeleteStringTableType() {
                try {
                        $this->Controller->Notification->doc_delete('kitten', '1', 'string');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testDocDeleteInvalidTableID() {
		try {
			$this->Controller->Notification->doc_delete('group', null, 'string');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->doc_delete('group', 'string', 'string');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->doc_delete('group', '0', 'string');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testDocDeleteNullDocumentID() {
		try {
			$this->Controller->Notification->doc_delete('group', '1', null);
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}
	}

	function testDocDeleteIntDocumentID() {
                try {
                        $this->Controller->Notification->doc_delete('group', '1', 1);
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testDocDeleteValid() {
		try {
			$this->Controller->Notification->doc_delete('group', '1', 'string');
			$this->pass();
		}

		catch(InvalidArgumentException $e) {
			$this->fail();
		}

		try {
			$this->Controller->Notification->doc_delete('project', '1', 'string');
			$this->pass();
		}

		catch(InvalidArgumentException $e) {
			$this->fail();
		}
	}

	function testDocCheckinInvalidTableType() {
		try {
			$this->Controller->Notification->doc_checkin(null, '1', '1');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->doc_checkin('1', '1', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->doc_checkin('kitten', '1', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testDocCheckinInvalidTableID() {
		try {
			$this->Controller->Notification->doc_checkin('group', null, '1');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->doc_checkin('group', 'string', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->doc_checkin('group', '0', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testDocCheckinInvalidDocID() {
		try {
			$this->Controller->Notification->doc_checkin('group', '1', null);
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->doc_checkin('group', '1', 'string');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->doc_checkin('group', '1', '0');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testDocCheckinValid() {
		try {
			$this->Controller->Notification->doc_checkin('group', '1', '1');
			$this->pass();
		}

		catch(InvalidArgumentException $e) {
			$this->fail();
		}

		try {
			$this->Controller->Notification->doc_checkin('project', '1', '1');
			$this->pass();
		}

		catch(InvalidArgumentException $e) {
			$this->fail();
		}
	}

	function testDocCheckoutInvalidTableType() {
		try {
			$this->Controller->Notification->doc_checkout(null, null, null);
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}
		
		try {
			$this->Controller->Notification->doc_checkout('1', null, null);
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

		try {
			$this->Controller->Notification->doc_checkout('kitten', null, null);
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}
	}

	function testDocCheckoutInvalidTableID() {
		try {
			$this->Controller->Notification->doc_checkout('group', 'string', null);
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

		try {
			$this->Controller->Notification->doc_checkout('group', null, null);
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

		try {
			$this->Controller->Notification->doc_checkout('group', '0', null);
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}
	}

	function testDocCheckoutInvalidDocID() {
		try {
			$this->Controller->Notification->doc_checkout('group', '1', 'string');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

		try {
			$this->Controller->Notification->doc_checkout('group', '1', '0');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

		try {
			$this->Controller->Notification->doc_checkout('group', '1', null);
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}
	}

	function testDocCheckoutValid() {
		try {
			$this->Controller->Notification->doc_checkout('group', '1', '1');
			$this->pass();
		}

		catch(InvalidArgumentException $e) {
			$this->fail();
		}

		try {
			$this->Controller->Notification->doc_checkout('project', '1', '1');
			$this->pass();
		}

		catch(InvalidArgumentException $e) {
			$this->fail();
		}
	}

	function testDocCancelCheckoutInvalidTableType() {
		try {
			$this->Controller->Notification->doc_cancel_checkout(null, '1', '1');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}
	
		try {
			$this->Controller->Notification->doc_cancel_checkout('1', '1', '1');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

		try {
			$this->Controller->Notification->doc_cancel_checkout('kitten', '1', '1');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}
	}

	function testDocCancelCheckoutInvalidTableID() {
		try {
			$this->Controller->Notification->doc_cancel_checkout('group', null, '1');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

		try {
			$this->Controller->Notification->doc_cancel_checkout('group', 'string', '1');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

		try {
			$this->Controller->Notification->doc_cancel_checkout('group', '0', '1');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}
	}

	function testDocCancelCheckoutInvalidDocID() {
		try {
			$this->Controller->Notification->doc_cancel_checkout('group', '1', null);
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

		try {
			$this->Controller->Notification->doc_cancel_checkout('group', '1', 'string');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

		try {
			$this->Controller->Notification->doc_cancel_checkout('group', '1', '0');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}
	}

	function testDocCancelCheckoutValid() {
		try {
			$this->Controller->Notification->doc_cancel_checkout('group', '1', '1');
			$this->pass();
		}

		catch(InvalidArgumentException $e) {
			$this->fail();
		}

		try {
			$this->Controller->Notification->doc_cancel_checkout('project', '1', '1');
			$this->pass();
		}

		catch(InvalidArgumentException $e) {
			$this->fail();
		}
	}

	function testGroupAddInvalidGroupID() {
		try {
			$this->Controller->Notification->group_add(null);
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

		try {
			$this->Controller->Notification->group_add('string');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

		try {
			$this->Controller->Notification->group_add('0');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}
	}

	function testGroupAddValid() {
		try {
			$this->Controller->Notification->group_add('1');
			$this->pass();
		}

		catch(InvalidArgumentException $e) {
			$this->fail();
		}
	}

	function testGroupEditInvalidGroupID() {
		try {
			$this->Controller->Notification->group_edit(null);
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

		try {
			$this->Controller->Notification->group_edit('string');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

		try {
			$this->Controller->Notification->group_edit('0');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}
	}

	function testGroupEditValid() {
		try {
			$this->Controller->Notification->group_edit('1');
			$this->pass();
		}

		catch(InvalidArgumentException $e) {
			$this->fail();
		}
	}

	function testGroupDeleteNullGroup() {
		try {
			$this->Controller->Notification->group_delete(null);
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}
	}

	function testGroupDeleteIntGroup() {
		try {
			$this->Controller->Notification->group_delete(1);
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}
	}

	function testGroupDeleteValid() {
			$this->Controller->Notification->group_delete('group');
	}

	function testGroupRemoveUserInvalidGroupID() {
		try {
			$this->Controller->Notification->group_removeuser(null, '1');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

		try {
			$this->Controller->Notification->group_removeuser('string', '1');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

		try {
			$this->Controller->Notification->group_removeuser('0', '1');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}
	}

	function testGroupRemoveUserInvalidUserID() {
		try {
			$this->Controller->Notification->group_removeuser('1', null);
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

		try {
			$this->Controller->Notification->group_removeuser('1', 'string');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

		try {
			$this->Controller->Notification->group_removeuser('1', '0');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}
	}

	function testGroupRemoveUserValid() {
		try {
			$this->Controller->Notification->group_removeuser('1', '1');
			$this->pass();
		}

		catch(InvalidArgumentException $e) {
			$this->fail();
		}
	}

/*
	function testGroupAcceptInvalidGroupID() {
		try {
			$this->Controller->Notification->group_accept(null, '1');
			$this->fail();
		}
	
		catch(InvalidArgumentException $e) {
			$this->pass();
		}	

		try {
			$this->Controller->Notification->group_accept('string', '1');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

		try {
			$this->Controller->Notification->group_accept('0', '1');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}
	}

	function testGroupAcceptInvalidUserID() {
		try {
			$this->Controller->Notification->group_accept('1', null);
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

		try {
                  	$this->Controller->Notification->group_accept('1', 'string');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
                try {
                        $this->Controller->Notification->group_accept('1', '0');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testGroupAcceptValid() {
		try {
                        $this->Controller->Notification->group_accept('1', '1');
                        $this->pass();
                }

                catch(InvalidArgumentException $e) {
                        $this->fail();
                }
	}
*/

	function testGroupLeaveInvalidGroupID() {
		try {
			$this->Controller->Notification->group_leave(null, '1');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->group_leave('string', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->group_leave('0', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testGroupLeaveInvalidUserID() {
		try {
			$this->Controller->Notification->group_leave('1', null);
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->group_leave('1', 'string');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->group_leave('1', '0');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testGroupLeaveValid() {
		try {
			$this->Controller->Notification->group_leave('1', '1');
			$this->pass();
		}

		catch(InvalidArgumentException $e) {
			$this->fail();
		}
	}

	function testGroupPromoteInvalidGroupID() {
		try {
			$this->Controller->Notification->group_promote(null, '1', 'string');
			$this->fail();
		}
	
		catch(InvalidArgumentException $e) {
			$this->pass();
		}
	
                try {
                        $this->Controller->Notification->group_promote('string', '1', 'string');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }


                try {
                        $this->Controller->Notification->group_promote('0', '1', 'string');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testGroupPromoteInvalidUserID() {
		try {
			$this->Controller->Notification->group_promote('1', null, 'string'); 
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->group_promote('1', 'string', 'string');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->group_promote('1', '0', 'string');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testGroupPromoteInvalidRole() {
		try {
			$this->Controller->Notification->group_promote('1', '1', null);
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->group_promote('1', '1', '0');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}	

	function testGroupPromoteValid() {
		try {
			$this->Controller->Notification->group_promote('1', '1', 'role');
			$this->pass();
		}

		catch(InvalidArgumentException $e) {
			$this->fail();
		}
	}

	function testGroupDemoteInvalidGroupID() {
		try {
			$this->Controller->Notification->group_demote(null, '1', 'string');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->group_demote('string', '1', 'string');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->group_demote('0', '1', 'string');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testGroupDemoteInvalidUserID() {
		try {
			$this->Controller->Notification->group_demote('1', null, 'string');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->group_demote('1', 'string', 'string');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->group_demote('1', '0', 'string');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testGroupDemoteInvalidRole() {
		try {
			$this->Controller->Notification->group_demote('1', '1', null);
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->group_demote(1, 1, 1);
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testProjectAddInvalidProjectID() {
		try {
			$this->Controller->Notification->project_add(null);
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->project_add('string');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->project_add('0');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testProjectAddValid() {
		try {
			$this->Controller->Notification->project_add('1');
			$this->pass();
		}

		catch(InvalidArgumentException $e ) {
			$this->fail();
		}
	}	
	
	function testProjectEditInvalidProjectID() {
               try {
                        $this->Controller->Notification->project_edit(null);
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->project_edit('string');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->project_edit('0');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
        }

        function testProjectEditValid() {
                try {
                        $this->Controller->Notification->project_edit(1);
                        $this->pass();
                }

                catch(InvalidArgumentException $e ) {
                        $this->fail();
                }
        }

	function testProjectDeleteInvalidProject() {
		try {
			$this->Controller->Notification->project_delete(null);
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->project_delete(1);
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testProjectDeleteValid() {
		try {
			$this->Controller->Notification->project_delete('project');
			$this->pass();
		}

		catch(InvalidArgumentException $e) {
			$this->fail();
		}
	}

	function testProjectRemoveUserInvalidProjectID() {
		try {
			$this->Controller->Notification->project_removeuser(null, '1');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->project_removeuser('string', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->project_removeuser('0', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}
	
	function testProjectRemoveUserInvalidUserID() {
		try {
			$this->Controller->Notification->project_removeuser('1', null);
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->project_removeuser('1', 'string');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->project_removeuser('1', '0');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}
		
	function testProjectRemoveUserValid() {
		try {
			$this->Controller->Notification->project_removeuser('1', '1');
			$this->pass();
		}

		catch(InvalidArgumentException $e) {
			$this->fail();
		}
	}
/*	
	function testProjectAcceptInvalidProjectID() {
		try {
			$this->Controller->Notification->project_accept(null, '1');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->project_accept('string', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->project_accept('0', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testProjectAcceptInvalidUserID() {
		try {
			$this->Controller->Notification->project_accept('1', null);
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->project_accept('1', 'string');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->project_accept('1', '0');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testProjectAcceptValid() {
		try {
			$this->Controller->Notification->project_accept('1', '1');
			$this->pass();
		}

		catch(InvalidArgumentException $e) {
			$this->fail();
		}
	}
*/
	function testProjectLeaveInvalidProjectID() {
		try {
			$this->Controller->Notification->project_leave(null, '1');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->project_leave('string', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->project_leave('0', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testProjectLeaveInvalidUserID() {
		try {
			$this->Controller->Notification->project_leave('1', null);
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->project_leave('1', 'string');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->project_leave('1', '0');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

	}

	function testProjectLeaveValid() {
		try {
			$this->Controller->Notification->project_leave('1', '1');
			$this->pass();
		}

		catch(InvalidArgumentException $e) {
			$this->fail();
		}
	}

	function testProjectPromoteInvalidProjectID() {
		try {
			$this->Controller->Notification->project_promote(null, '1', 'role');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->project_promote('string', '1', 'role');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->project_promote('0', '1', 'role');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testProjectPromoteInvalidUserID() {
		try {
			$this->Controller->Notification->project_promote('1', null, 'role');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->project_promote('1', 'string', 'role');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->project_promote('1', '0', 'role');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}
	
	function testProjectPromoteInvalidRole() {
		try {
			$this->Controller->Notification->project_promote('1', '1', null);
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->project_promote(1, 1, 1);
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testProjectPromoteValid() {
		try {
			$this->Controller->Notification->project_promote('1', '1', 'role');
			$this->pass();
		}

		catch(InvalidArgumentException $e) {
			$this->fail();
		}
	}

	function testProjectDemoteInvalidProjectID() {
		try {
			$this->Controller->Notification->project_demote(null, '1', 'role');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->project_demote('string', '1', 'role');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->project_demote('0', '1', 'role');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}
	
	function testProjectDemoteInvalidUserID() {
		try {
			$this->Controller->Notification->project_demote('1', null, 'role');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->project_demote('1', 'string', 'role');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->project_demote('1', '0', 'role');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}
	
	function testProjectDemoteInvalidRole() {
		try {
			$this->Controller->Notification->project_demote(1, 1, null);
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->project_demote(1, 1, 0);
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testProjectDemoteValid() {
		try {
			$this->Controller->Notification->project_demote(1, 1, 'role');
			$this->pass();
		}

		catch(InvalidArgumentException $e) {
			$this->fail();
		}
	}
	
	function testNoteAddInvalidTableType() {
		try {
			$this->Controller->Notification->note_add(null, '1', '1');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();	
		}

	        try {
                        $this->Controller->Notification->note_add('0', '1', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->note_add('kitten', '1', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testNoteAddInvalidTableID() {
		try {
			$this->Controller->Notification->note_add('group', null, '1');	
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->note_add('group', 'string', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->note_add('group', '0', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testNoteAddInvalidNoteID() {
		try {
			$this->Controller->Notification->note_add('group', '1', null);
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->note_add('group', '1', 'string');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->note_add('group', '1', '0');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testNoteAddValid() {
		try {
			$this->Controller->Notification->note_add('group', '1', '1');
			$this->pass();
		}

		catch(InvalidArgumentException $e) {
			$this->fail();
		}
                
		try {
                        $this->Controller->Notification->note_add('project', '1', '1');
                        $this->pass();
                }

                catch(InvalidArgumentException $e) {
                        $this->fail();
                }
	}

	function testNoteEditInvalidTableType() {
		try {
			$this->Controller->Notification->note_edit(null, '1', '1');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}
               
		 try {
                        $this->Controller->Notification->note_edit('0', '1', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->note_edit('kitten', '1', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testNoteEditInvalidTableID() {
	        try {
                        $this->Controller->Notification->note_edit('group', null, '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                 try {
                        $this->Controller->Notification->note_edit('group', 'string', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->note_edit('group', '0', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testNoteEditInvalidNoteID() {
                try {
                        $this->Controller->Notification->note_edit('group', '1', null);
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                 try {
                        $this->Controller->Notification->note_edit('group', '1', 'string');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->note_edit('group', '1', '0');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testNoteEditValid() {
                try {
                        $this->Controller->Notification->note_edit('group', '1', '1');
                        $this->pass();
                }

                catch(InvalidArgumentException $e) {
                        $this->fail();
                }

                try {
                        $this->Controller->Notification->note_edit('project', '1', '1');
                        $this->pass();
                }

                catch(InvalidArgumentException $e) {
                        $this->fail();
                }
	}

	function testNoteDeleteInvalidTableType() {
                try {
                        $this->Controller->Notification->note_delete(null, '1', 'note');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                 try {
                        $this->Controller->Notification->note_delete('1', '1', 'note');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->note_delete('kitten', '1', 'note');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testNoteDeleteInvalidTableID() {
                try {
                        $this->Controller->Notification->note_delete('group', null, 'note');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                 try {
                        $this->Controller->Notification->note_delete('group', 'string', 'note');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->note_delete('group', '0', 'note');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testNoteDeleteInvalidNote() {
                try {
                        $this->Controller->Notification->note_delete('group', 'string', null);
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->note_delete('group', '1', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testNoteDeleteValid() {
                try {
                        $this->Controller->Notification->note_delete('group', '1', '1');
                        $this->pass();
                }

                catch(InvalidArgumentException $e) {
                        $this->fail();
                }

                try {
                        $this->Controller->Notification->note_delete('project', '1', '1');
                        $this->pass();
                }

                catch(InvalidArgumentException $e) {
                        $this->fail();
                }
	}

	function testTypeAddInvalidTableType() {
		try {
			$this->Controller->Notification->type_add(null, '1', '1');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->type_add('1', '1', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->type_add('kitten', '1', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testTypeAddInvalidTableID() {
	        try {
                        $this->Controller->Notification->type_add('group', null, '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->type_add('group', 'string', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->type_add('group', '0', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testTypeAddInvalidTypeID() {
                try {
                        $this->Controller->Notification->type_add('group', '1', null);
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->type_add('group', '1', 'string');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->type_add('group', '1', '0');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testTypeAddValid() {
                try {
                        $this->Controller->Notification->type_add('group', '1', '1');
                        $this->pass();
                }

                catch(InvalidArgumentException $e) {
                        $this->fail();
                }

                try {
                        $this->Controller->Notification->type_add('project', '1', '1');
                        $this->pass();
                }

                catch(InvalidArgumentException $e) {
                        $this->fail();
                }
	}

	function testTypeEditInvalidTableType() {
		try {
			$this->Controller->Notification->type_edit(null, '1', '1');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->type_edit('1', '1', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->type_edit('kitten', '1', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testTypeEditInvalidTableID() {
                try {
                        $this->Controller->Notification->type_edit('group', null, '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->type_edit('group', 'string', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->type_edit('group', '0', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testTypeEditInvalidTypeID() {
                try {
                        $this->Controller->Notification->type_edit('group', '1', null);
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->type_edit('group', '1', 'string');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->type_edit('group', '1', '0');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testTypeEditValid() {
	        try {
                        $this->Controller->Notification->type_edit('group', '1', '1');
                        $this->pass();
                }

                catch(InvalidArgumentException $e) {
                        $this->fail();
                }

                try {
                        $this->Controller->Notification->type_edit('project', '1', '1');
                        $this->pass();
                }

                catch(InvalidArgumentException $e) {
                        $this->fail();
                }
	}

	function testTypeDeleteInvalidTableType() {
		try {
			$this->Controller->Notification->type_delete(null, '1', '1');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->type_delete('1', '1', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->type_delete('kitten', '1', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testTypeDeleteInvalidTableID() {
                try {
                        $this->Controller->Notification->type_delete('group', null, '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->type_delete('group', 'string', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->type_delete('group', '0', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testTypeDeleteInvalidTypeID() {
                try {
                        $this->Controller->Notification->type_delete('group', '1', null);
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
                
		try {
                        $this->Controller->Notification->type_delete('group', '1', 'string');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->type_delete('group', '1', '0');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testTypeDeleteValid() {
                try {
                        $this->Controller->Notification->type_delete('group', '1', '1');
                        $this->pass();
                }

                catch(InvalidArgumentException $e) {
                        $this->fail();
                }

                try {
                        $this->Controller->Notification->type_delete('project', '1', '1');
                        $this->pass();
                }

                catch(InvalidArgumentException $e) {
                        $this->fail();
                }
	}

	function testUrlAddInvalidTableType() {
		try {
			$this->Controller->Notification->url_add(null, '1', '1');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->url_add('1', '1', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->url_add('kitten', '1', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testUrlAddInvalidTableID() {
                try {
                        $this->Controller->Notification->url_add('group', null, '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->url_add('group', 'string', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->url_add('group', '0', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testUrlAddInvalidUrlID() {
                try {
                        $this->Controller->Notification->url_add('group', '1', null);
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	
                try {
                        $this->Controller->Notification->url_add('group', '1', 'string');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->url_add('group', '1', '0');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testUrlAddValid() {
                try {
                        $this->Controller->Notification->url_add('group', 1, 1);
                        $this->pass();
                }

                catch(InvalidArgumentException $e) {
                        $this->fail();
                }

                try {
                        $this->Controller->Notification->url_add('project', 1, 1);
                        $this->pass();
                }

                catch(InvalidArgumentException $e) {
                        $this->fail();
                }
	}

	function testUrlEditInvalidTableType() {
		try {
			$this->Controller->Notification->url_edit(null, '1', '1');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->url_edit('0', '1', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->url_edit('kitten', '1', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testUrlEditInvalidTableID() {
                try {
                        $this->Controller->Notification->url_edit('group', null, '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->url_edit('group', 'string', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->url_edit('group', '0', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testUrlEditInvalidUrlID() {
                try {
                        $this->Controller->Notification->url_edit('group', '1', null);
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->url_edit('group', '1', 'string');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->url_edit('group', '1', '0');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testUrlEditValid() {
                try {
                        $this->Controller->Notification->url_edit('group', '1', '1');
                        $this->pass();
                }

                catch(InvalidArgumentException $e) {
                        $this->fail();
                }

                try {
                        $this->Controller->Notification->url_edit('project', '1', '1');
                        $this->pass();
                }

                catch(InvalidArgumentException $e) {
                        $this->fail();
                }
	}

	function testUrlDeleteInvalidTableType() {
		try {
			$this->Controller->Notification->url_delete(null, '1', '1');
			$this->fail();
		}

		catch(InvalidArgumentException $e) {
			$this->pass();
		}

                try {
                        $this->Controller->Notification->url_delete('0', '1', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->url_delete('kitten', '1', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testUrlDeleteInvalidTableID() {
                try {
                        $this->Controller->Notification->url_delete('group', null, '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->url_delete('group', 'string', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->url_delete('group', '0', '1');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testUrlDeleteInvalidLabel() {
                try {
                        $this->Controller->Notification->url_delete('group', '1', null);
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->url_delete('group', '1', 'string');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }

                try {
                        $this->Controller->Notification->url_delete('group', '1', '0');
                        $this->fail();
                }

                catch(InvalidArgumentException $e) {
                        $this->pass();
                }
	}

	function testUrlDeleteValidGroup() {
                try {
                        $this->Controller->Notification->url_delete('group', '1', '1');
                        $this->pass();
                }

                catch(InvalidArgumentException $e) {
                        $this->fail();
                }
	}

	function testUrlDeleteValidProject() {
                try {
                        $this->Controller->Notification->url_delete('project', '1', '1');
                        $this->pass();
                }

                catch(InvalidArgumentException $e) {
                        $this->fail();
                }
	}
	
	function endTest() {
		unset($this->Controller);
		ClassRegistry::flush();
	}	
}		
?>
