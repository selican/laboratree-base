<?php
class Navigation extends AppModel
{
	public $name = 'Navigation';
	public $actsAs = array(
		'Tree',
	);
	public $useTable = 'navigation';

	var $belongsTo = array(
		'Perm' => array(
			'className' => 'Perm',
			'foreignKey' => 'permission_id',
		),
	);

	/**
	 * Converts a record to a ExtJS Store node
	 *
	 * @param array $item   Item
	 * @param array $params Parameters
	 *
	 * @return array ExtJS Store Node
	 */
	public function toNode($item, $params = array())
	{
		if(empty($item))
		{
			throw new InvalidArgumentException('Invalid Item');
		}

		if(!is_array($item))
		{
			throw new InvalidArgumentException('Invalid Item');
		}

		if(!empty($params))
		{
			if(!is_array($params))
			{
				throw new InvalidArgumentException('Invalid Parameters');
			}
		}

		if(isset($params['model']))
		{
			$params['model'] = $this->name;
		}

		if(!is_string($params['model']))
		{
			throw new RuntimeException('Invalid Model');
		}

		$model = $params['model'];

		if(!isset($item[$model]))
		{
			throw new InvalidArgumentException('Invalid Model Key');
		}

		$required = array(
			'id',
			'controller',
			'action',
			'role',
			'type',
			'title',
			'url',
		);

		foreach($required as $key)
		{
			if(!array_key_exists($key, $item[$model]))
			{
				throw new InvalidArgumentException('Missing ' . strtoupper($key) . ' Key');
			}
		}

		$node = array(
			'id'         => $item[$model]['id'],
			'controller' => $item[$model]['controller'],
			'action'     => $item[$model]['action'],
			'role'       => $item[$model]['role'],
			'type'       => $item[$model]['type'],
			'title'      => $item[$model]['title'],
			'url'        => $item[$model]['url'],
			'uiProvider' => 'col',
			'leaf'       => false,
			'cls'        => 'x-tree-node-' . $item[$model]['type'],
			'expandable' => true,
		);

		if($item[$model]['type'] != 'node')
		{
			$node['draggable'] = false;
		}

		return $node;
	}

	/**
	 * Generates a Navigation tree for a controller, action, and role
	 *
	 * @param string $controller Controller
	 * @param string $action     Action
	 * @param array  $context    Context
	 *
	 * @return array Navigation Tree
	 */
	public function tree($controller, $action, $context)
	{
		if(empty($controller))
		{
			throw new InvalidArgumentException('Invalid Controller');
		}

		if(!is_string($controller))
		{
			throw new InvalidArgumentException('Invalid Controller');
		}

		if(empty($action))
		{
			throw new InvalidArgumentException('Invalid Action');
		}

		if(!is_string($action))
		{
			throw new InvalidArgumentException('Invalid Action');
		}

		if(!empty($context))
		{
			if(!is_array($context))
			{
				throw new InvalidArgumentException('Invalid Context');
			}
		}

		$tabs = array();

		$root = $this->find('first', array(
			'conditions' => array(
				$this->name . '.type' => 'root',
			),
			'recursive' => -1,
		));
		if(empty($root))
		{
			return $this->_process($tabs, $context);
		}

		$defaults = $this->find('threaded', array(
			'conditions' => array(
				$this->name . '.type'       => 'node',
				$this->name . '.controller' => null,
				$this->name . '.action'     => null,
				$this->name . '.lft >'      => $root[$this->name]['lft'],
				$this->name . '.rght <'     => $root[$this->name]['rght'],
			),
			'contain' => array(
				'Perm',
			),
			'order' => $this->name . '.lft',
		));
		if(!empty($defaults))
		{
			$tabs = array_merge($defaults, $tabs);
		}

		$croot = $this->find('first', array(
			'conditions' => array(
				$this->name . '.type'       => 'controller',
				$this->name . '.controller' => $controller,
			),
			'recursive' => -1,
		));
		if(empty($croot))
		{
			return $this->_process($tabs, $context);
		}

		$defaults = $this->find('threaded', array(
			'conditions' => array(
				$this->name . '.type'   => 'node',
				$this->name . '.action' => null,
				$this->name . '.lft >'  => $croot[$this->name]['lft'],
				$this->name . '.rght <' => $croot[$this->name]['rght'],
			),
			'contain' => array(
				'Perm',
			),
			'order' => $this->name . '.lft',
		));
		if(!empty($defaults))
		{
			$tabs = array_merge($defaults, $tabs);
		}

		$aroot = $this->find('first', array(
			'conditions' => array(
				$this->name . '.type'       => 'action',
				$this->name . '.controller' => $controller,
				$this->name . '.action'     => $action,
			),
			'recursive' => -1,
		));
		if(empty($aroot))
		{
			return $this->_process($tabs, $context);
		}

		$nodes = $this->find('threaded', array(
			'conditions' => array(
				$this->name . '.type'   => 'node',
				$this->name . '.lft >'  => $aroot[$this->name]['lft'],
				$this->name . '.rght <' => $aroot[$this->name]['rght'],
			),
			'contain' => array(
				'Perm',
			),
			'order' => $this->name . '.lft',
		));
		if(!empty($nodes))
		{
			$tabs = array_merge($nodes, $tabs);
		}

		return $this->_process($tabs, $context);
	}

	/**
	 * Sorts Tabs
	 *
	 * @internal
	 * 
	 * @param array $a Tab
	 * @param array $b Tab
	 *
	 * @return integer Sort Priority
	 */
	function _sortTabs($a, $b)
	{
		if(empty($a))
		{
			throw new InvalidArgumentException('Invalid Tab');
		}

		if(!is_array($a))
		{
			throw new InvalidArgumentException('Invalid Tab');
		}

		if(empty($b))
		{
			throw new InvalidArgumentException('Invalid Tab');
		}

		if(!is_array($b))
		{
			throw new InvalidArgumentException('Invalid Tab');
		}

		if($a[$this->name]['lft'] == $b[$this->name]['lft'])
		{
			return 0;
		}

		return ($a[$this->name]['lft'] < $b[$this->name]['lft']) ? -1 : 1;
	}

	/**
	 * Process tabs with context
	 *
	 * @internal
	 *
	 * @param array $tabs    Tabs
	 * @param array $context Context
	 *
	 * @return array Array of Processed Tabs and Subtabs
	 */
	function _process($tabs, $context)
	{
		if(empty($tabs))
		{
			throw new InvalidArgumentException('Invalid Tabs');
		}

		if(!is_array($tabs))
		{
			throw new InvalidArgumentException('Invalid Tabs');
		}
		
		if(!empty($context))
		{
			if(!is_array($context))
			{
				throw new InvalidArgumentException('Invalid Context');
			}
		}

		usort($tabs, array($this, '_sortTabs'));

		$main = array();
		$action = array();
		for($i = 0; $i < sizeof($tabs); $i++)
		{
			$tab = $tabs[$i];

			if(isset($tab['Perm']) && !empty($tab['Perm']['id']))
			{
				$sections = explode('.', $tab['Perm']['name']);
				if(isset($context['permissions'][$sections[0]]))
				{
					if($tab['Perm']['mask'] & $context['permissions'][$sections[0]])
					{

						$main[] = $tab;
					}
				}
			}
			else
			{
				$main[] = $tab;
			}

			if(isset($tab['children']) && !empty($tab['children']))
			{
				$tab[$this->name]['type'] = 'current';

				for($j = 0; $j < sizeof($tab['children']); $j++)
				{
					$subtab = $tab['children'][$j];

					if(isset($subtab['Perm']) && !empty($subtab['Perm']['id']))
					{
						$sections = explode('.', $subtab['Perm']['name']);
						if(isset($context['permissions'][$sections[0]]))
						{
							if($subtab['Perm']['mask'] & $context['permissions'][$sections[0]])
							{
								$action[] = $subtab;
							}
						}
					}
					else
					{
						$action[] = $subtab;
					}
				}
			}
		}

		usort($action, array($this, '_sortTabs'));

		return array(
			'tabs' => $main,
			'subtabs' => $action,
		);
	}
}
?>
