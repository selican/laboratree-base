<?php
class AppController extends Controller
{
	var $uses = array(
		'Group',
		'User',
		'GroupsUsers',
		'ProjectsUsers',
	);

	var $components = array(
		'Acl',
		'Auth',
		'Cookie',
		'Security',
		'Session',
		'RequestHandler',
		'Recaptcha',
		'Image',
		'PermissionCmp',
		'Plugin',
		'Lucene',
	);

	var $helpers = array(
		'Html',
		'Javascript',
		'Form',
		'Cache',
		'Text',
		'Number',
		'Navigation',
		'Plugin',
	);

	var $cacheAction = '10 minutes';

	var $plugins = array();

	function beforeFilter()
	{
		$this->Cookie->domain = '.' . Configure::read('Site.domain');

		$this->Auth->userModel = 'User';
		$this->Auth->fields = array(
			'username' => 'username',
			'password' => 'password',
		);
		$this->Auth->userScope = array(
			'User.confirmed' => 1,
		);

		$this->Auth->loginAction = array('controller' => 'users', 'action' => 'login');
		$this->Auth->loginRedirect = array('controller' => 'users', 'action' => 'dashboard');
		$this->Auth->logoutRedirect = array('controller' => 'users', 'action' => 'login');
		$this->Auth->autoRedirect = false;

		$this->Auth->allow(array('display'));

		$this->RequestHandler->setContent('extjs', 'text/html');

		$admin = Configure::read('Routing.admin');
		if(isset($this->params[$admin]) && $this->params[$admin])
		{
			$this->layout = 'admin';
			// TODO: Change userScope to require admin
		}

		/* TODO : Rething this or at least make these more meaningful */
		$c = null;
		$a = null;
		$p = null;

		if(isset($this->params['controller']))
		{
			$c = $this->params['controller'];
			if(isset($this->params['action']))
			{
				$a = $this->params['action'];
			}

			if(isset($this->params['pass']) && !empty($this->params['pass']))
			{
				$p = $this->params['pass'];
			}
		}

		$this->set('c', $c);
		$this->set('a', $a);
		$this->set('p', $p);

		$this->set('pageName', null);

		$context = array();

		if($this->Auth->user())
		{
			$user = $this->Auth->user();

			try {
				$this->User->activity($user['User']['id']);
			} catch(RuntimeException $e) {
				// Ignore
			}

			if($user['User']['changepass'] != 0 && (!isset($this->params['requested']) || $this->params['requested'] == 0))
			{
				$this->Session->setFlash('A password change is required.', 'default', array(), 'auth');

				if(isset($this->params['controller']) && isset($this->params['action']))
				{
					if($this->params['controller'] != 'users')
					{
						$this->redirect('/users/changepass');
						return;
					}

					if(!in_array($this->params['action'], array('logout', 'changepass')))
					{
						$this->redirect('/users/changepass');
						return;
					}
				}
			}

			$this->set('grouplist', $this->grouplist());
		}

		$this->set('context', $context);

		$this->plugins = $this->Plugin->load();
		$this->set('plugins', $this->plugins);
	}

	/**
	 * Wrapper for Get Role functions for Users and Groups
	 */
	function get_role($table_type, $table_id, $user_id = '')
	{
		if(!is_numeric($table_id) || $table_id < 1)
		{
			return false;
		}

		if(!empty($user_id) && (!is_numeric($user_id) || $user_id < 1))
		{
			return false;
		}

		if($table_type == 'group')
		{
			return $this->get_group_role($table_id, $user_id);
		}
		else if($table_type == 'project')
		{
			return $this->get_project_role($table_id, $user_id);
		}

		return false;
	}

	/**
	 * Gets the Group Role Name using a Group ID and optionally a User ID.
	 * @param $group_id Group ID
	 * @param $user_id optional User ID which defaults to the current user
	 * @return Name of Group Role
	*/
	function get_group_role($group_id, $user_id = '')
	{
		if(!is_numeric($group_id) || $group_id < 1)
		{
			return false;
		}

		if(!empty($user_id) && (!is_numeric($user_id) || $user_id < 1))
		{
			return false;
		}

		if(empty($user_id))
		{
			if(!$this->Session->check('Auth.User.id'))
			{
				return false;
			}

			$user_id = $this->Session->read('Auth.User.id');
		}

		$relationship = $this->GroupsUsers->find('first', array('conditions' => array('GroupsUsers.group_id' => $group_id, 'GroupsUsers.user_id' => $user_id), 'recursive' => -1, 'fields' => array('GroupsUsers.role_id')));
		if(!empty($relationship))
		{
			$role = $this->Role->find('first', array(
				'conditions' => array(
					'Role.id' => $relationship['GroupsUsers']['role_id'],
				),
				'fields' => array(
					'Role.name',
				),
				'recursive' => -1,
			));
			if(!empty($role))
			{
				return $role['Role']['name'];
			}
		}

		return false;
	}

	/**
	 * Gets the Project Role Name using a Project ID and optionally a User ID.
	 * @param $project_id Project ID
	 * @param $user_id optional User ID which defaults to the current user
	 * @return Name of Project Role
	*/
	function get_project_role($project_id, $user_id = '')
	{
		if(!is_numeric($project_id) || $project_id < 1)
		{
			return false;
		}

		if(!empty($user_id) && (!is_numeric($user_id) || $user_id < 1))
		{
			return false;
		}

		if(empty($user_id))
		{
			if(!$this->Session->check('Auth.User.id'))
			{
				return false;
			}

			$user_id = $this->Session->read('Auth.User.id');
		}

		$relationship = $this->ProjectsUsers->find('first', array('conditions' => array('ProjectsUsers.project_id' => $project_id, 'ProjectsUsers.user_id' => $user_id), 'recursive' => -1, 'fields' => array('ProjectsUsers.role_id')));
		if(!empty($relationship))
		{
			$role = $this->Role->find('first', array(
				'conditions' => array(
					'Role.id' => $relationship['ProjectsUsers']['role_id'],
				),
				'fields' => array(
					'Role.name',
				),
				'recursive' => -1,
			));
			if(!empty($role))
			{
				return $role['Role']['name'];
			}
		}

		return false;
	}

	function grouplist()
	{
		$list = array(
			'Groups' => array(),
			'Projects' => array(),
		);

		$groups = $this->GroupsUsers->find('list', array('conditions' => array('GroupsUsers.user_id' => $this->Session->read('Auth.User.id')), 'fields' => array('Group.id', 'Group.name'), 'recursive' => 1, 'order' => 'Group.name'));
		foreach($groups as $group_id => $name)
		{
			$list['Groups']['group:' . $group_id] = $name;
		}

		$projects = $this->ProjectsUsers->find('list', array('conditions' => array('ProjectsUsers.user_id' => $this->Session->read('Auth.User.id')), 'fields' => array('Project.id', 'Project.name'), 'recursive' => 1, 'order' => 'Project.name'));
		foreach($projects as $project_id => $name)
		{
			$list['Projects']['project:' . $project_id] = $name;
		}

		return $list;
	}
}
?>
