<?php
class PluginHelper extends AppHelper
{
	var $helpers = array(
		'Html',
	);

	function dashboard(&$plugins, $data_url)
	{
		if(!empty($plugins))
		{
			if(!is_array($plugins))
			{
				throw new InvalidArgumentException('Plugins must be an array');
			}
		}

		if(empty($data_url))
		{
			throw new InvalidArgumentException('Data URL must not be empty');
		}

		if(!is_scalar($data_url))
		{
			throw new InvalidArgumentException('Data URL must be a string');
		}

		$data = '';
		foreach($plugins as $plugin)
		{
			$feature = Inflector::underscore($plugin);
			if(file_exists(APP . DS . 'plugins' . DS . $feature . DS . 'controllers' . DS . 'components' . DS . 'dashboard.php'))
			{
				$data .= "\t\tlaboratree." . $feature . ".makePortlet('" . $this->Html->url($data_url) . "');\n";
			}
		}

		return $this->output($data);
		//return $data;
	}
}
?>
