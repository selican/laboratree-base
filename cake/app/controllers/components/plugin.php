<?php
class PluginComponent extends Object
{
	var $uses = array(
		'Group',
		'Project',
		'Perm',
	);

	var $components = array(
		'PermissionCmp',
	);

	var $listeners = array();

	function _loadModels(&$object)
	{
		foreach($object->uses as $model)
		{
			$object->{$model} =& ClassRegistry::init($model);
		}
	}

	function initialize(&$controller)
	{
		$this->Controller =& $controller;
		$this->_loadModels($this);
	}

	function startup(&$controller) {}

	/**
	 * Adds Event Listener
	 *
	 * @param string   $context  Plugin Context
	 * @param string   $action   Event
	 * @param function $callback Callback
	 *
	 * @throws InvalidArgumentException
	 */
	function addListener($context, $action, $callback)
	{
		if(empty($context))
		{
			throw new InvalidArgumentException('Invalid context');
		}

		if(!is_scalar($context))
		{
			throw new InvalidArgumentException('Invalid context');
		}

		if(empty($action))
		{
			throw new InvalidArgumentException('Invalid action');
		}

		if(!is_scalar($action))
		{
			throw new InvalidArgumentException('Invalid action');
		}

		if(empty($callback))
		{
			throw new InvalidArgumentException('Invalid callback');
		}

		if(!is_callable($callback))
		{
			throw new InvalidArgumentException('Invalid callback');
		}

		$this->listeners[$action][$context] = $callback;
	}

	/**
	 * Removes Event Listener
	 *
	 * @param string $context Plugin Context
	 * @param string $action  Event
	 *
	 * @throws InvalidArgumentException
	 */
	function removeListener($context, $action)
	{
		if(empty($context))
		{
			throw new InvalidArgumentException('Invalid context');
		}

		if(!is_scalar($context))
		{
			throw new InvalidArgumentException('Invalid context');
		}

		if(empty($action))
		{
			throw new InvalidArgumentException('Invalid action');
		}

		if(!is_scalar($action))
		{
			throw new InvalidArgumentException('Invalid action');
		}

		if(isset($this->listeners[$action][$context]))
		{
			unset($this->lisetners[$action][$context]);
		}
	}

	/**
	 * Broadcasts an Event to Event Listeners
	 *
	 * @param string $action    Event
	 * @param array  $arguments Callback Arguments
	 *
	 * @throws InvalidArgumentException
	 *
	 * @return boolean Broadcast Status
	 */
	function broadcastListeners($action, $arguments = array())
	{
		if(empty($action))
		{
			throw new InvalidArgumentException('Invalid action');
		}

		if(!is_scalar($action))
		{
			throw new InvalidArgumentException('Invalid action');
		}

		if(!isset($this->listeners[$action]))
		{
			return false;
		}

		if(!empty($arguments))
		{
			if(!is_array($arguments))
			{
				throw new InvalidArgumentException('Invalid arguments');
			}
		}

		foreach($this->listeners[$action] as $context => $callback)
		{
			call_user_func_array($callback, $arguments);
		}

		return true;
	}

	/**
	 * Adds a Permission
	 *
	 * @param string  $name   Permission Name
	 * @param string  $title  Permission Title
	 * @param integer $mask   Permission Mask
	 * @param integer $parent Permission Parent
	 *
	 * @throws InvalidArgumentException
	 * @throws RuntimeException
	 *
	 * @return integer Permission ID
	 */
	function addPermission($name, $title, $mask = null, $parent = null)
	{
		if(empty($name))
		{
			throw new InvalidArgumentException('Invalid name');
		}

		if(!is_scalar($name))
		{
			throw new InvalidArgumentException('Invalid name');
		}

		if(empty($title))
		{
			throw new InvalidArgumentException('Invalid title');
		}

		if(!is_scalar($title))
		{
			throw new InvalidArgumentException('Invalid title');
		}

		if(!empty($mask))
		{
			if(!is_numeric($mask))
			{
				throw new InvalidArgumentException('Invalid mask');
			}
		}

		if(!empty($parent))
		{
			if(!is_numeric($parent))
			{
				throw new InvalidArgumentException('Invalid parent');
			}
		}

		if(!empty($defaults))
		{
			if(!is_array($defaults))
			{
				throw new InvalidArgumentException('Invalid defaults');
			}
		}

		if(empty($parent))
		{
			$parent = $this->Perm->field('id', array(
				'name' => 'root',
				'parent_id' => NULL,
			));
			if(empty($parent))
			{
				throw new RuntimeException('Unable to find permission root');
			}
		}

		$permission = $this->Perm->find('first', array(
			'conditions' => array(
				'Perm.name' => $name,
				'Perm.parent_id' => $parent,
			),
			'recursive' => -1,
		));
		if(!empty($permission))
		{
			return $permission['Perm']['id'];
		}

		$data = array(
			'Perm' => array(
				'name' => $name,
				'title' => $title,
				'mask' => $mask,
				'parent_id' => $parent,
			),
		);
		$this->Perm->create();
		if(!$this->Perm->save($data))
		{
			throw new RuntimeException('Unable to save permission');
		}

		return $this->Perm->id;
	}

	function addPermissionDefaults($defaults)
	{
		if(empty($defaults))
		{
			throw new InvalidArgumentException('Invalid Defaults');
		}

		if(!is_array($defaults))
		{
			throw new InvalidArgumentException('Invalid Defaults');
		}
		
		$groups = $this->Group->find('all', array(
			'fields' => array(
				'id',
			),
			'recursive' => -1,
		));
		foreach($groups as $group)
		{
			$this->PermissionCmp->setup('group', $group['Group']['id'], $defaults);
		}

		$projects = $this->Project->find('all', array(
			'fields' => array(
				'id',
			),
			'recursive' => -1,
		));
		foreach($projects as $project)
		{
			$this->PermissionCmp->setup('project', $project['Project']['id'], $defaults);
		}
	}

	/**
	 * Lodas Plugins and Generates Dynamic Files
	 *
	 * @return array Loaded Plugins
	 */
	function load()
	{
		$loaded = array();

		$plugins = Configure::listObjects('plugin');
		foreach($plugins as $plugin)
		{
			$file = APP . DS . 'plugins' . DS . Inflector::underscore($plugin) . DS . 'config' . DS . 'bootstrap.php';
			if(file_exists($file))
			{
				require_once($file);
				$loaded[] = $plugin;
			}
		}

		// Generate Site File
		$this->site();

		// Generate Validation
		$this->validation($loaded);

		// Generate Links
		$this->links($loaded);

		return $loaded;
	}

	/**
	 * Generates Dynamic Site File
	 */
	function site()
	{
		/* Start */
		$data  = "laboratree.site = {\n";

		/* Permissions */
		$root = $this->Perm->find('first', array(
			'conditions' => array(
				'Perm.parent_id' => null,
			),
			'recursive' => -1,
		));
		if(empty($root))
		{
			$this->cakeError('internal_error', array('action' => 'Retrieve', 'resource' => 'Permission Root'));
			return;
		}

		$permissions = $this->Perm->find('all', array(
			'conditions' => array(
				'Perm.parent_id' => $root['Perm']['id'],
			),
			'recursive' => -1,
		));

		$data .= "\t'permissions': {\n";
		
		$tree = array();
		foreach($permissions as $permission)
		{
			if(!array_key_exists($permission['Perm']['name'], $tree))
			{
				$tree[$permission['Perm']['name']] = array();
			}

			$children = $this->Perm->children($permission['Perm']['id'], true);
			foreach($children as $child)
			{
				$nodes = explode('.', $child['Perm']['name']);
				array_shift($nodes);

				$this->PermissionCmp->toTree($tree[$permission['Perm']['name']], $nodes, $child['Perm']['mask']);
			}
		}

		$this->PermissionCmp->toJSON($tree, $data, 2);

		$data .= "\t},\n";

		/* End */
		$data .= "};";

		file_put_contents(JS . DS . 'site.js', $data);
	}

	/**
	 * Generates Dynamic Validation File
	 *
	 * @param array $plugins Plugins
	 *
	 * @throws InvalidArgumentException
	 */
	function validation($plugins)
	{
		if(!empty($plugins))
		{
			if(!is_array($plugins))
			{
				throw new InvalidArgumentException('Invalid plugins');
			}
		}

		$ruleList = array(
			'alphaNumeric',
			'between',
			'blank',
			'isUnique',
			'notEmpty',
			'email',
			'url',
			'minLength',
			'maxLength',
			'numeric',
			'decimal',
			'inList',
			'boolean',
			'ip',
		);

		$validation = array();

		$models = Configure::listObjects('model');
		foreach($models as $model)
		{
			App::import('Model', $model);
			$modelObj = new $model();

			if(isset($modelObj->validate) && !empty($modelObj->validate))
			{
				$validation['core'][$model] = $modelObj->validate;
			}

			unset($modelObj);
		}

		foreach($plugins as $plugin)
		{
			$path = APP . DS . 'plugins' . DS . Inflector::underscore($plugin) . DS . 'models' . DS;
			$models = Configure::listObjects('model', $path, false);

			foreach($models as $model)
			{
				App::import('Model', $plugin . '.' . $model);
				$modelObj = new $model();

				if(isset($modelObj->validate) && !empty($modelObj->validate))
				{
					$validation[$plugin][$model] = $modelObj->validate;
				}
			}

			unset($modelObj);
		}

		$data = <<<JAVASCRIPT
laboratree.validation = {
	'alphaNumeric': function(v) {
		return Ext.form.VTypes.alphanum(v);
	},
	'between': function(v, min, max) {
		var len = v.length;
		return (len >= min && len <= max) ? true : false;
	},
	'blank': function(v) {
		var rxp = /[^\\s]/;
		return !rxp.test(v);
	},
	'isUnique': function(v) {
		return true;
	},
	'notEmpty': function(v) {
		var rxp = /[^\s]+/m;
		return rxp.test(v);
	},
	'email': function(v) {
		return Ext.form.VTypes.email(v);
	},
	'url': function(v) {
		var regexp = /(((^https?)|(^ftp)):\/\/([\-\w]+\.)+\w{2,3}(\/[%\-\w]+(\.\w{2,})?)*(([\w\-\.\?\\\/+@&#;`~=%!]*)(\.\w{2,})?)*\/?)/i;
		return regexp.test(v);	
	},
	'minLength': function(v, min) {
		var len = v.length;
		return (len >= min);
	},
	'maxLength': function(v, max) {
		var len = v.length;
		return (len <= max);
	},
	'numeric': function(v) {
		var rxp = /^[-+]?\\b[0-9]*\\.?[0-9]+\\b$/;
		return rxp.test(v);
	},
	'decimal': function(v) {
		var rxp = /^\d+(\.\d{1,2})?$/;
		return rxp.test(v);
	},
	'inList': function(v, list) {
		if(list.hasOwnProperty(v)) {
			return true;
		}
		return false;
	},
	'boolean': function(v) {
		if(v === 0 || v === 1 || v === '0' || v === '1' || v === true || v === false)
		{
			return true;
		}
		return false;
	},
	'ip': function(v) {
		var rxp = /^(?:(?:25[0-5]|2[0-4][0-9]|(?:(?:1[0-9])?|[1-9]?)[0-9])\.){3}(?:25[0-5]|2[0-4][0-9]|(?:(?:1[0-9])?|[1-9]?)[0-9])$/;
		return rxp.test(v);
	},
	'rxp': function(v, pattern) {
		var rxp = new RegExp(pattern);
		return rxp.test(v);
	}
};

Ext.apply(Ext.form.VTypes, {

JAVASCRIPT;
		foreach($validation as $source => $models)
		{
			foreach($models as $model => $fields)
			{
				foreach($fields as $field => $rules)
				{
					if(array_key_exists('rule', $rules))
					{
						$rule = $rules['rule'];
						if(is_array($rule))
						{
							$rule = $rule[0];
						}

						$rules = array(
							$rule => $rules,
						);
					}

					$key = Inflector::variable(str_replace('-', '_', $model . '_' . $field));
					$data .= "\t'$key': function(v) {\n";

					foreach($rules as $rule_id => $rule)
					{
						if(is_string($rule))
						{
							$rule = array(
								'rule' => $rule,
							);
						}

						if(!array_key_exists('rule', $rule))
						{
							continue;
						}

						$ruleName = $rule['rule'];
						if(is_array($rule['rule']))
						{
							$ruleName = $rule['rule'][0];
						}

						$message = 'Field is required.';
						if(array_key_exists('message', $rule))
						{
							$message = $rule['message'];
						}

						switch($ruleName) {
							case 'between':
								$data .= "\t\tif(!laboratree.validation['between'](v, " . $rule['rule'][1] . ", " . $rule['rule'][2] . ")) {\n";
								$data .= "\t\t\tthis.{$key}Text = '" . addslashes($message) . "';\n";
								$data .= "\t\t\treturn false;\n";
								$data .= "\t\t}\n";
								break;
							case 'minLength':
								$data .= "\t\tif(!laboratree.validation['minLength'](v, " . $rule['rule'][1] . ")) {\n";
								$data .= "\t\t\tthis.{$key}Text = '" . addslashes($message) . "';\n";
								$data .= "\t\t\treturn false;\n";
								$data .= "\t\t}\n";
								break;
							case 'maxLength':
								$data .= "\t\tif(!laboratree.validation['maxLength'](v, " . $rule['rule'][1] . ")) {\n";
								$data .= "\t\t\tthis.{$key}Text = '" . addslashes($message) . "';\n";
								$data .= "\t\t\treturn false;\n";
								$data .= "\t\t}\n";
								break;
							case 'inList':
								$data .= "\t\tif(!laboratree.validation['inList'](v, {\n";
								for($i = 0; $i < count($rule['rule'][1]); $i++)
								{
									$entry = $rule['rule'][1][$i];

									if(($i + 1) == count($rule['rule'][1]))
									{
										$data .= "\t\t\t'$entry': ''\n";
									}
									else
									{
										$data .= "\t\t\t'$entry': '',\n";
									}
								}
								$data .= "\t\t})) {\n";
								$data .= "\t\t\tthis.{$key}Text = '" . addslashes($message) . "';\n";
								$data .= "\t\t\treturn false;\n";
								$data .= "\t\t}\n";
								break;
							default:
								if(in_array($ruleName, $ruleList))
								{
									$data .= "\t\tif(!laboratree.validation['$ruleName'](v)) {\n";
									$data .= "\t\t\tthis.{$key}Text = '" . addslashes($message) . "';\n";
									$data .= "\t\t\treturn false;\n";
									$data .= "\t\t}\n";
								}
								else
								{
									$data .= "\t\tif(!laboratree.validation.rxp(v, '$ruleName')) {\n";
									$data .= "\t\t\tthis.{$key}Text = '" . addslashes($message) . "';\n";
									$data .= "\t\t\treturn false;\n";
									$data .= "\t\t}\n";
								}
						}
					}
					$data .= "\t\treturn true;\n";
					$data .= "\t},\n";
				}
			}
		}

		$data .= "\t'unused': {}\n";
		$data .= "});";

		file_put_contents(JS . DS . 'validation.js', $data);
	}

	/**
	 * Generates Dynamic Links file
	 *
	 * @params array $plugins Plugins
	 *
	 * @throws InvalidArgumentException
	 */
	function links($plugins)
	{
		if(!empty($plugins))
		{
			if(!is_array($plugins))
			{
				throw new InvalidArgumentException('Invalid Plugins');
			}
		}

		$sources = array();

		$sources['core'] = Configure::listObjects('controller');

		foreach($plugins as $plugin)
		{
			$source = Inflector::underscore($plugin);

			$path = APP . DS . 'plugins' . DS . $source . DS . 'controllers' . DS;
			$sources[$source] = Configure::listObjects('controller', $path, false);
		}

		$exclude = array(
			'App',
			'Pages',
			'Navigation',
		);

		$links = array();
		foreach($sources as $source => $controllers)
		{
			foreach($controllers as $controller)
			{
				if($source == 'core')
				{
					if(in_array($controller, $exclude))
					{
						continue;
					}

					App::import('Controller', $controller);
				}
				else
				{
					App::import('Controller', $source . '.' . $controller);
				}

				$controllerClass = $controller . 'Controller';
				$controllerName = strtolower($controller);

				$ref = new ReflectionClass($controllerClass);

				$actions = $ref->getMethods();
				foreach($actions as $action)
				{
					if($action->class != $controllerClass)
					{
						continue;
					}

					$actionPrefix = 'core';
					$actionName = $action->name;

					if(($pos = strpos($actionName, '_')) !== false)
					{
						/* Skip 'internal' actions */
						if($pos == 0)
						{
							continue;
						}
						
						list($actionPrefix, $actionName) = explode('_', $actionName);
						if($actionPrefix == 'admin')
						{
							continue;
						}
					}

					if(in_array($actionName, array('beforeFilter')))
					{
						continue;
					}

					$method = $ref->getMethod($action->name);
					$parameters = $method->getParameters();

					$arguments = array();
					foreach($parameters as $parameter)
					{
						$arguments[] = $parameter->name;
					}

					$links[$actionPrefix][$source][$controllerName][$actionName] = $arguments;
				}
			}
		}

		$data  = "laboratree.links = {\n";
		$data .= "\t'base': '" . Configure::read('Site.full') . "',\n";

		foreach($links as $prefix => $sources)
		{
			$indent = "\t";

			if($prefix != 'core')
			{
				$data .= "\t'$prefix': {\n";
				$indent = "\t\t";
			}

			foreach($sources as $source => $controllers)
			{
				foreach($controllers as $controller => $actions)
				{
					$data .= "$indent'$controller': {\n";

					$start = $indent . "\t";
					foreach($actions as $action => $arguments)
					{
						$destination = $controller . '/' . $action;
						if($prefix != 'core')
						{
							$destination = $prefix . '/' . $destination;
						}

						if($source != 'core')
						{
							if($source != $controller)
							{
								$destination = $source . '/' . $destination;
							}
						}

						$data .= "$start'$action': '" . Configure::read('Site.full') . $destination;
						for($i = 0; $i < count($arguments); $i++)
						{
							$data .= '/{' . $i . '}';
						}
						$data .= "',\n";
					}

					$data .= "$indent},\n";

				}

			}

			if($prefix != 'core')
			{
				$data .= "\t},\n";
			}
		}

		$data .= '};';

		file_put_contents(JS . DS . 'links.js', $data);
	}
}
?>
