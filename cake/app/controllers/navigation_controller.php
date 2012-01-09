<?php
class NavigationController extends AppController {
	var $name = 'Navigation';

	var $uses = array(
		'Navigation',
		'Perm',
	);

	var $components = array(
		'Auth',
		'Security',
		'Session',
		'RequestHandler',
		'PermissionCmp',
		'Plugin',
	);

	function beforeFilter()
	{
		$this->Security->validatePost = false;
		
		parent::beforeFilter();
	}

	/**
	 * Returns a Navigation Tree for RequestHandler
	 *
	 * @param string $controller Controller
	 * @param string $action     Action
	 * @param array  $context    Context
	 *
	 * @return array Navigation Tree
	 */
	function tree($controller, $action, $context)
	{
		if(empty($controller))
		{
			$this->cakeError('missing_field', array('field' => 'Controller'));
			return;
		}

		if(!is_string($controller))
		{
			$this->cakeError('invalid_field', array('field' => 'Controller'));
			return;
		}

		if(empty($action))
		{
			$this->cakeError('missing_field', array('field' => 'Action'));
			return;
		}

		if(!is_string($action))
		{
			$this->cakeError('invalid_field', array('field' => 'Action'));
			return;
		}

		if(!empty($context))
		{
			if(!is_array($context))
			{
				$this->cakeError('invalid_field', array('field' => 'Context'));
				return;
			}
		}

		return $this->Navigation->tree($controller, $action, $context);
	}

	/**
	 * Allows Management of the Navigation Tree
	 */
	function admin_index()
	{
		$this->pageTitle = 'Admin Navigation - ' . $this->Session->read('Auth.User.id');
		$this->set('pageName', $this->Session->read('Auth.User.id') . ' - Admin Navigation');

		if($this->RequestHandler->prefers('json'))
		{
			$conditions = array();

			$parent_id = null;
			if(isset($this->params['form']['node']) && !preg_match('/xnode-/', $this->params['form']['node']))
			{
				$parent_id = $this->params['form']['node'];
				
				$parent = $this->Navigation->find('first', array(
					'conditions' => array(
						'Navigation.id' => $parent_id,
					),
					'recursive' => -1,
				));
				if(empty($parent))
				{
					$this->cakeError('invalid_field', array('field' => 'Parent ID'));
					return;
				}

				$conditions = array(
					'Navigation.lft >' => $parent['Navigation']['lft'],
					'Navigation.rght <' => $parent['Navigation']['rght'],
				);
			}

			$navigation = $this->Navigation->find('threaded', array(
				'conditions' => $conditions,
				'order' => 'Navigation.lft',
				'recursive' => 1,
			));
			try {
				$nodes = $this->Navigation->toNodes($navigation);
			} catch(Exception $e) {
				$this->cakeError('internal_error', array('action' => 'Convert', 'resource' => 'Navigation Entry'));
				return;
			}
			$this->set('nodes', $nodes);
		}
	}

	/**
	 * Adds a Navigation Item to the Navigation Tree
	 *
	 * @param integer $parent_id Parent ID
	 */
	function admin_add($parent_id = '')
	{
		if(!$this->RequestHandler->prefers('json'))
		{
			$this->cakeError('error404');
			return;
		}

		if(empty($parent_id))
		{
			$this->cakeError('missing_field', array('field' => 'Parent ID'));
			return;
		}

		if(!is_numeric($parent_id) || $parent_id < 1)
		{
			$this->cakeError('invalid_field', array('field' => 'Parent ID'));
			return;
		}

		$parent = $this->Navigation->find('first', array(
			'conditions' => array(
				'Navigation.id' => $parent_id,
			),
			'order' => 'Navigation.lft',
			'recursive' => -1,
		));
		if(empty($parent))
		{
			$this->cakeError('invalid_field', array('field' => 'Parent ID'));
			return;
		}

		if(empty($this->data))
		{
			$this->cakeError('invalid_field', array('field' => 'Data'));
			return;
		}

		if(empty($this->data['Navigation']['controller']))
		{
			$this->data['Navigation']['controller'] = $parent['Navigation']['controller'];
		}

		if(empty($this->data['Navigation']['action']))
		{
			$this->data['Navigation']['action'] = $parent['Navigation']['action'];
		}

		if(empty($this->data['Navigation']['role']))
		{
			$this->data['Navigation']['role'] = $parent['Navigation']['role'];
		}

		$this->data['Navigation']['parent_id'] = $parent_id;

		$response = array(
			'success' => false,
		);

		foreach($this->data['Navigation'] as $field => $value)
		{
			if(empty($value))
			{
				$this->data['Navigation'][$field] = null;
			}
		}

		$this->Navigation->create();
		if($this->Navigation->save($this->data))
		{
			$response['success'] = true;
		}

		$this->set('response', $response);
	}

	/**
	 * Edits a Navigation Item in the Navigation Tree
	 *
	 * @param integer $navigation_id Navigation Item ID
	 */
	function admin_edit($navigation_id = '')
	{
		if(!$this->RequestHandler->prefers('json'))
		{
			$this->cakeError('error404');
			return;
		}

		if(empty($navigation_id))
		{
			$this->cakeError('missing_field', array('field' => 'Navigation ID'));
			return;
		}

		if(!is_numeric($navigation_id))
		{
			$this->cakeError('invalid_field', array('field' => 'Navigation ID'));
			return;
		}

		$item = $this->Navigation->find('first', array(
			'conditions' => array(
				'Navigation.id' => $navigation_id,
			),
			'order' => 'Navigation.lft',
			'recursive' => -1,
		));
		if(empty($item))
		{
			$this->cakeError('invalid_field', array('field' => 'Navigation ID'));
			return;
		}

		$response = array(
			'success' => false,
		);

		$action = 'edit';
		if(isset($this->params['form']['action']))
		{
			$action = $this->params['form']['action'];
		}

		switch($action) {
			case 'edit':
				try {
					$node = $this->Navigation->toNode($item);
				} catch(Exception $e) {
					$this->cakeError('internal_error', array('action' => 'Convert', 'resource' => 'Navigation Entry'));
					return;
				}
				$response = array(
					'success' => true,
					'item' => $node,
				);
				break;
		}

		if(!empty($this->data))
		{
			$this->data['Navigation']['id'] = $navigation_id;
			$this->data['Navigation']['parent_id'] = $item['Navigation']['parent_id'];

			foreach($this->data['Navigation'] as $field => $value)
			{
				if(empty($value))
				{
					$this->data['Navigation'][$field] = null;
				}
			}

			if($this->Navigation->save($this->data))
			{
				$response = array(
					'success' => true,
				);
			}
		}

		$this->set('response', $response);
	}

	/**
	 * Deletes a Navigation Item from the Navigation Tree
	 *
	 * @param integer $node_id Navigation Item ID
	 */
	function admin_delete($node_id = '')
	{
		if(!$this->RequestHandler->prefers('json'))
		{
			$this->cakeError('error404');
			return;
		}

		if(empty($node_id))
		{
			$this->cakeError('missing_field', array('field' => 'Node ID'));
			return;
		}

		if(!is_numeric($node_id))
		{
			$this->cakeError('invalid_field', array('field' => 'Node ID'));
			return;
		}

		$node = $this->Navigation->find('first', array(
			'conditions' => array(
				'Navigation.id' => $node_id,
			),
			'recursive' => -1,
		));
		if(empty($node))
		{
			$this->cakeError('invalid_field', array('field' => 'Node ID'));
			return;
		}

		$response = array('success' => 1);

		if(!$this->Navigation->delete($node_id))
		{
			$response['success'] = 0;
		}

		$this->set('response', $response);
	}

	/**
	 * Reorders a Navigation Item in the Navigation Tree
	 */
	function admin_reorder()
	{
		if(!$this->RequestHandler->prefers('json'))
		{
			$this->cakeError('error404');
			return;
		}

		if(!isset($this->params['form']['node']))
		{
			$this->cakeError('missing_field', array('field' => 'Node'));
			return;
		}

		if(!is_numeric($this->params['form']['node']))
		{
			$this->cakeError('invalid_field', array('field' => 'Node'));
			return;
		}

		if(!isset($this->params['form']['delta']))
		{
			$this->cakeError('missing_field', array('field' => 'Delta'));
			return;
		}

		if(!is_numeric($this->params['form']['delta']))
		{
			$this->cakeError('invalid_field', array('field' => 'Delta'));
			return;
		}

		$node_id = $this->params['form']['node'];
		$delta = $this->params['form']['delta'];

		$node = $this->Navigation->find('first', array(
			'conditions' => array(
				'Navigation.id' => $node_id,
			),
			'recursive' => -1,
		));
		if(empty($node))
		{
			$this->cakeError('invalid_field', array('field' => 'Node'));
			return;
		}

		$response = array('success' => 1);

		if($delta > 0)
		{
			if($this->Navigation->movedown($node_id, abs($delta)))
			{
				$response = array('success' => 1);
			}
			else
			{
				$this->cakeError('internal_error', array('action' => 'Reorder', 'resource' => 'Navigation'));
				return;
			}
		}
		else if($delta < 0)
		{
			if($this->Navigation->moveup($node_id, abs($delta)))
			{
				$response = array('success' => 1);
			}
			else
			{
				$this->cakeError('internal_error', array('action' => 'Reorder', 'resource' => 'Navigation'));
				return;
			}
		}

		$this->set('response', $response);
	}

	/**
	 * Reparents a Navigation Item in the Navigation Tree
	 */
	function admin_reparent()
	{
		if(!$this->RequestHandler->prefers('json'))
		{
			$this->cakeError('error404');
			return;
		}

		if(!isset($this->params['form']['node']))
		{
			$this->cakeError('missing_field', array('field' => 'Node'));
			return;
		}

		if(!is_numeric($this->params['form']['node']))
		{
			$this->cakeError('invalid_field', array('field' => 'Node'));
			return;
		}

		if(!isset($this->params['form']['parent']))
		{
			$this->cakeError('missing_field', array('field' => 'Parent'));
			return;
		}

		if(!is_numeric($this->params['form']['parent']))
		{
			$this->cakeError('invalid_field', array('field' => 'Parent'));
			return;
		}

		if(!isset($this->params['form']['position']))
		{
			$this->cakeError('missing_field', array('field' => 'Position'));
			return;
		}

		if(!is_numeric($this->params['form']['position']))
		{
			$this->cakeError('invalid_field', array('field' => 'Position'));
			return;
		}

		$node_id = $this->params['form']['node'];
		$parent_id = $this->params['form']['parent'];
		$position = $this->params['form']['position'];

		$node = $this->Navigation->find('first', array(
			'conditions' => array(
				'Navigation.id' => $node_id,
			),
			'recursive' => -1,
		));
		if(empty($node))
		{
			$this->cakeError('invalid_field', array('field' => 'Node'));
			return;
		}

		$parent = $this->Navigation->find('first', array(
			'conditions' => array(
				'Navigation.id' => $parent_id,
			),
			'recursive' => -1,
		));
		if(empty($parent))
		{
			$this->cakeError('invalid_field', array('field' => 'Parent'));
			return;
		}

		$data = array(
			'Navigation' => array(
				'id' => $node_id,
				'parent_id' => $parent_id,
			),
		);
		if(!$this->Navigation->save($data))
		{
			$this->cakeError('internal_error', array('action' => 'Reparent', 'resource' => 'Navigation'));
			return;
		}

		$this->Navigation->moveup($node_id, true);
		if($position > 0)
		{
			$this->Navigation->movedown($node_id, $position);
		}

		$this->set('response', array('success' => 1));
	}
}
?>
