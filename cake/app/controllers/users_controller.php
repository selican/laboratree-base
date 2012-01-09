<?php
class UsersController extends AppController
{
	var $name = 'Users';

	var $uses = array(
		'User', 
		'GroupsUsers',
		'ProjectsUsers',
		'Word',
		'Help',
	);

	var $components = array(
		'Auth',
		'Security',
		'Session',
		'RequestHandler',
		'PermissionCmp',
		'Plugin',
		'Image',
		'FileCmp',
		'Recaptcha',
	);

	function beforeFilter()
	{
		$this->Auth->allow('home', 'login', 'logout', 'register', 'verify', 'resetlink', 'resetpass', 'forgotusername', 'test', 'external_status', 'openid');

		$this->Security->validatePost = false;

		parent::beforeFilter();
	}

	/**
	 * Redirects User to Dashboard or Login
	 */
	function home()
	{
		if($this->Session->check('Auth.User'))
		{
			$this->redirect('/users/dashboard');
		}
		else
		{
			$this->redirect('/users/login');
		}
		return;
	}

	/**
	 * Redirects to User Dashboard
	 */
	function index()
	{
		$this->redirect('/users/dashboard');
		return;
	}

	/**
	 * User Dashboard
	 */
	function dashboard()
	{
		$this->pageTitle = 'User Dashboard';
		$this->set('pageName', $this->Session->read('Auth.User.name') . ' - Dashboard');

		$context = array(
			'table_type' => 'user',
			'table_id' => $this->Session->read('Auth.User.id'),

			'user_id' => $this->Session->read('Auth.User.id'),

			'permissions' => array(
			),
		);
		$this->set('context', $context);

		if($this->RequestHandler->prefers('json'))
		{
			if(!isset($this->params['form']['model']))
			{
				$this->cakeError('missing_field', array('field' => 'Model'));
				return;
			}

			$model = $this->params['form']['model'];
			if(empty($model) || !is_string($model))
			{
				$this->cakeError('invalid_field', array('field' => 'Model'));
				return;
			}

			$plugin = Inflector::camelize($model);

			$builtin = true;
			if(!in_array($model, array('groups', 'projects', 'colleagues')))
			{
				$builtin = false;

				if(!in_array($plugin, $this->plugins))
				{
					$this->cakeError('invalid_field', array('field' => 'Model'));
					return;
				}
			}

			$list = array();
			if($builtin)
			{
				switch($model)
				{
					case 'groups':
						try {
							$groups = $this->GroupsUsers->groups($this->Session->read('Auth.User.id'));
							$list = $this->GroupsUsers->Group->toList('groups', $groups);
						} catch(Exception $e) {
							$this->cakeError('internal_error', array('action' => 'Retrieve', 'resource' => 'Groups'));
							return;
						}
						break;
					case 'projects':
						try {
							$projects = $this->ProjectsUsers->projects($this->Session->read('Auth.User.id'));
							$list = $this->ProjectsUsers->Project->toList('projects', $projects);
						} catch(Exception $e) {
							$this->cakeError('internal_error', array('action' => 'Retrieve', 'resource' => 'Projects'));
							return;
						}
						break;
					case 'colleagues':
						try {
							$colleagues = $this->User->colleagues($this->Session->read('Auth.User.id'));
							$list = $this->User->toList('colleagues', $colleagues);
						} catch(Exception $e) {
							$this->cakeError('internal_error', array('action' => 'Retrieve', 'resource' => 'Colleagues'));
							return;
						}
						break;
				}
			}
			else
			{
				if(App::import('Component', $plugin . '.Dashboard'))
				{
					$dashboard = new DashboardComponent();
					$dashboard->initialize($this);

					$list = $dashboard->process('user', $this->Session->read('Auth.User.id'), $this->params);
				}
			}

			$this->set('list', $list);
		}
	}

	/**
	 * Allows the user to manage their account
	 */
	function account()
	{
		$user = $this->User->find('first', array(
			'conditions' => array(
				'User.id' => $this->Session->read('Auth.User.id'),
			),
			'recursive' => 1,
		));
		if(empty($user))
		{
			$this->cakeError('internal_error', array('action' => 'Manage', 'resource' => 'User Account', 'additional' => 'Find'));
			return;
		}

		$this->pageTitle = 'Account Management';
		$this->set('pageName', $user['User']['name'] . ' - Account');

		if(!empty($this->data))
		{
			$this->data['User']['id'] = $this->Session->read('Auth.User.id');

			if(isset($this->data['User']['email']))
			{
				$this->data['User']['email'] = trim($this->data['User']['email']);

				if($this->data['User']['email'] != $user['User']['email'])
				{
					if(!preg_match(VALID_EMAIL, $this->data['User']['email']))
					{
						// TODO: Invalidate Field
					}

					$hash = md5(uniqid('', true));
					$data = array(
						'User' => array(
							'id'   => $this->Session->read('Auth.User.id'),
							'email'     => $this->data['User']['email'],
							'hash'      => $hash,
							'confirmed' => '0',
						),
					);
					if(!$this->User->save($data))
					{
						// TODO: Invalidate Field
					}

					try {
						$this->Plugin->broadcastListeners('user.changeemail', array(
							$this->Session->read('Auth.User.id'),
							$this->data['User']['email'],
						));
					} catch(Exception $e) {
						$this->cakeError('internal_error', array('action' => 'Manage', 'resource' => 'User Account'));
						return;
					}
				}
			}

			if(isset($this->data['User']['picture']) && $this->FileCmp->is_uploaded_file($this->data['User']['picture']['tmp_name']))
			{
				try {
					if(($filename = $this->Image->user($this->data['User']['picture']['tmp_name'])) === false)
					{
						unset($this->data['User']['picture']);
					}
					else
					{
						/* Remove Old Picture */
						if(!empty($user['User']['picture']))
						{
							$destination = IMAGES . 'users/' . $user['User']['picture'] . '.png';
							if($this->FileCmp->file_exists($destination))
							{
								$this->FileCmp->unlink($destination);
							}
		
							$destination = IMAGES . 'users' . $user['User']['picture'] . '_thumb.png';
							if(file_exists($destination))
							{
								unlink($destination);
							}
						}

						$this->data['User']['picture'] = $filename;
					}
				} catch(Exception $e) {
					$this->cakeError('internal_error', array('action' => 'Process', 'resource' => 'Picture', 'additional' => 'Picture'));
					return;
				}
			}
			else
			{
				unset($this->data['User']['picture']);
			}

			if(!$this->User->save($this->data))
			{
				// TODO: Change to validation data
			}

			$this->Session->setFlash('User Information Updated', 'default', array(), 'status');
			$this->redirect('/users/account');
			return;
		}

		if($this->RequestHandler->prefers('json'))
		{
			$this->set('response', array(
				'success' => true,
				'user' => $user,
			));
		}
	}

	/**
	 * Logs the user in
	 *
	 * @param string $url Base64 Encoded URL
	 */
	function login($url = null)
	{
		if(!is_null($url) && !empty($url))
		{
			$url = base64_decode($url);
			$this->Session->write('Auth.redirect', $url);
		}

		// redirect to the login page if the last page visited was XML or Javascript
		if(preg_match('/\.(xml|js)$/', $this->Session->read('Auth.redirect')))
		{
			$this->Session->write('Auth.redirect', '/');
		}

		if($this->Auth->login($this->data))
		{
			$this->redirect($this->Auth->redirect());
			return;
		}
			
		$this->set('url', $url);
	}

	/**
	 * Logs the user out
	 */
	function logout()
	{
		$this->redirect($this->Auth->logout());
	}

	/**
	 * Registered a User
	 *
	 * @param string $url Base64 Encoded URL
	 */
	function register($url = '')
	{
		/* TODO: Change to normal function? */

		$this->pageTitle = 'Register';
		$this->set('pageName', 'Register');

		if($this->RequestHandler->prefers('json'))
		{
			if(!empty($this->data))
			{
				$response = array(
					'success' => false,
				);

				/* Check Captcha */
				if(!$this->Recaptcha->valid($this->params['form']))
				{
					$response = array(
						'success' => false,
						'errors' => array(
							'User' => array(
								'captcha' => 'Your response must match the captcha challenge.',
							),
						),
					);
					$this->set('response', $response);
					$this->render();
					return;
				}

				/* Check if passwords match */
				if($this->data['User']['password'] != $this->Auth->password($this->data['User']['password2']))
				{
					$response = array(
						'success' => false,
						'errors' => array(
							'password' => 'Your passwords must match.',
						),
					);
					$this->set('response', $response);
					$this->render();
					return;
				}

				$plain = $this->data['User']['password2'];
				unset($this->data['User']['password2']);

				/* All usernames in lowercase */
				$this->data['User']['username'] = strtolower($this->data['User']['username']);

				/* Trim white spaces from username */
				$this->data['User']['username'] = trim($this->data['User']['username']);

				/* Create Verification Hash */
				$hash = md5(uniqid('', true));
				$this->data['User']['hash'] = $hash;
				$this->data['User']['confirmed'] = 0;

				$this->data['User']['activity'] = date('Y-m-d H:i:s', strtotime('January 1 1970 00:00:00 UTC'));
				$this->data['User']['registered'] = date('Y-m-d H:i:s');

				if(!$this->User->save($this->data))
				{
					$response = array(
						'success' => false,
						'errors' => array(
							'User' => $this->User->validationErrors,
						),
					);
				}

				// TODO: Send Email Verify

				$response = array(
					'success' => true,
					'msg' => 'Your account is now created. An email message will be sent to the email address you specified. Please follow the link in the email message to confirm your account.',
				);
			}

			$this->set('response', $response);
		}
	}

	/**
	 * Verifies a User based on a Hash from a Link in their Email
	 *
	 * @param integer $user_id User ID
	 * @param string  $hash    Inbox Hash
	 * @param string  $url     Base64 Encoded URL
	 */
	function verify($user_id = '', $hash = '', $url = '')
	{
		if(empty($user_id))
		{
			$this->cakeError('invalid_field', array('field' => 'Link'));
			return;
		}

		if(!is_numeric($user_id) || $user_id < 1)
		{
			$this->cakeError('invalid_field', array('field' => 'Link'));
			return;
		}

		if(empty($hash))
		{
			$this->cakeError('invalid_field', array('field' => 'Link'));
			return;
		}

		$user = $this->User->find('first', array(
			'conditions' => array(
				'User.id' => $user_id,
				'User.hash' => $hash,
			),
			'recursive' => -1,
		));
		if(empty($user))
		{
			$this->cakeError('invalid_field', array('field' => 'Link'));
			return;
		}

		if(empty($url))
		{
			$url = base64_encode('/users/dashboard');
		}

		$data = array(
			'User' => array(
				'id' => $user_id,
				'hash' => null,
				'confirmed' => 1,
			),
		);
		$this->User->save($data);

		$this->redirect('/users/login/' . $url);
		return;
	}

	/**
	 * Resets the User's Password from an Email Link
	 *
	 * @param integer $user_id User ID
	 * @param string  $hash    Inbox Hash
	 */
	function resetlink($user_id = '', $hash = '')
	{
		if(empty($user_id))
		{
			$this->cakeError('invalid_field', array('field' => 'Link'));
			return;
		}

		if(!is_numeric($user_id) || $user_id < 1)
		{
			$this->cakeError('invalid_field', array('field' => 'Link'));
			return;
		}

		if(empty($hash))
		{
			$this->cakeError('invalid_field', array('field' => 'Link'));
			return;
		}

		$user = $this->User->find('first', array(
			'conditions' => array(
				'User.id' => $user_id,
				'User.hash' => $hash,
			),
			'recursive' => -1,
		));
		if(empty($user))
		{
			$this->cakeError('invalid_field', array('field' => 'Link'));
			return;
		}

		$this->pageTitle = 'Reset User Password' . $user['User']['name'];
		$this->set('pageName', $user['User']['name'] . ' - Reset User Password');	

		$this->set('user', $user);
		$this->set('hash', $hash);

		if(!empty($this->data))
		{
			if($this->data['User']['password1'] != $this->data['User']['password2'])
			{
				$this->cakeError('invalid_field', array('field' => 'Password'));
				return;
			}

			/* Save User */
			$data = array(
				'User' => array(
					'id' => $user_id,
					'password' => $this->Auth->password($this->data['User']['password1']),
					'changepass' => 0,
					'hash' => '',
				),
			);
		
			if(!$this->User->save($data))
			{
				$this->cakeError('invalid_field', array('field' => 'Data'));
				return;
			}

			$this->Session->setFlash('Your password has been changed.', 'default', array(), 'status');
			$this->redirect('/users/login');
			return;
		}
	}

	/**
	 * Requests a Password Reset
	 */
	function resetpass()
	{
		$this->pageTitle = 'Reset Password';
		$this->set('pageName', 'Reset Password');

		if(!empty($this->data))
		{
			if(!isset($this->data['User']['username']))
			{
				$this->cakeError('missing_field', array('field' => 'Username'));
				return;
			}

			if(!isset($this->data['User']['email']))
			{
				$this->cakeError('missing_field', array('field' => 'Email'));
				return;
			}

			$user = $this->User->find('first', array(
				'conditions' => array(
					'User.username' => $this->data['User']['username'],
					'User.email' => $this->data['User']['email'],
				),
				'recursive' => -1,
			));
			if(empty($user))
			{
				$this->cakeError('invalid_field', array('field' => 'User'));
				return;
			}

			$hash = strtoupper(md5(uniqid('', true)));

			$this->User->id = $user['User']['id'];
			$this->User->saveField('hash', $hash);

			// TODO: Figure this out
			//$this->email_passwordrequest($this->data['User']['email'], $user['User']['id'], $hash);

			$this->Session->setFlash('An email will be sent to \'' . $this->data['User']['email'] . '\' with a password reset request.', 'default', array(), 'status');
			$this->redirect('/');
			return;
		}
	}

	/**
	 * Sends a Username reminder to User's email address
	 */
	function forgotusername()
	{
		$this->pageTitle = 'Forgotten Username';
		$this->set('pageName', 'Forgotten Username');

		if(!empty($this->data))
		{
			if(!isset($this->data['User']['email']))
			{
				$this->cakeError('missing_field', array('field' => 'Email'));
				return;
			}

			$user = $this->User->find('first', array(
				'conditions' => array(
					'User.email' => $this->data['User']['email'],
				),
				'recursive' => -1,
			));
			if(empty($user))
			{
				$this->cakeError('invalid_field', array('field' => 'Email'));
				return;
			}

			// TODO: Figure this out
			//$this->email_forgotusername($user['User']['email'], $user['User']['username']);

			$this->Session->setFlash('An email will be sent to \'' . $user['User']['email'] . '\' with the username.', 'default', array(), 'status');
			$this->redirect('/');

			return;
		}
	}

	/**
	 * Changes the User's Password
	 */
	function changepass()
	{
		$this->pageTitle = 'Change User Password';
		$this->set('pageName', 'Change User Password');	

		if(!empty($this->data))
		{
			$user = $this->User->find('first', array(
				'conditions' => array(
					'User.id' => $this->Session->read('Auth.User.id'),
				),
				'recursive' => -1,
			));
			if($user['User']['password'] != $this->Auth->password($this->data['User']['current']))
			{
				$this->cakeError('invalid_field', array('field' => 'Current Password'));
				return;
			}

			if($this->data['User']['password1'] != $this->data['User']['password2'])
			{
				$this->cakeError('invalid_field', array('field' => 'New Password'));
				return;
			}

			$this->data['User']['password'] = $this->data['User']['password1'];

			$this->User->set($this->data);
			if(!$this->User->validates(array(
				'fieldList' => array(
					'password',
				),
			)))
			{
				$error = 'Invalid password';
				if(isset($this->User->validationErrors['password']))
				{
					$error = $this->User->validationErrors['password'];
				}

				$this->Session->setFlash($error, 'default', array(), 'status');
				$this->redirect('/users/changepass');
				return;
			}

			$password = $this->Auth->password($this->data['User']['password1']);

			$data = array(
				'User' => array(
					'id' => $user['User']['id'],
					'password' => $password,
					'changepass' => 0,
				),
			);
			if(!$this->User->save($data))
			{
				$this->cakeError('internal_error', array('action' => 'Change', 'resource' => 'Password'));
				return;
			}

			$user['User']['password'] = $password;
			$user['User']['changepass'] = 0;
			$this->Auth->login($user);

			$this->Session->setFlash('Your password has been changed.', 'default', array(), 'status');
			$this->redirect('/users/dashboard');
			return;
		}
	}

	/**
	 * Help for Dashboard
	 */
	function help_dashboard() 
	{
		$this->pageTitle = 'Help - User - Dashboard';
		$this->set('pageName', 'Dashboard - Help');
	}

	/**
	 * Help for Account
	 */
	function help_account()
	{
		$this->pageTitle = 'Help - User - Account';
		$this->set('pageName', 'User - Account - Help');
	}

	/* TODO: Help for other Actions */
}
?>
