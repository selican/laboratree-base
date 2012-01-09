<?php
	if(isset($c) && !empty($c) && file_exists(CSS . $c . '.css'))
	{
		echo $html->css($c . '.css');
	}

	if(isset($plugins))
	{
		foreach($plugins as $plugin)
		{
			if(file_exists(APP . DS . 'plugins' . DS . $plugin . DS . 'views' . DS . 'elements' . DS . 'csshandler.ctp'))
			{
				echo $this->element('csshandler', array('plugin' => $plugin));
			}
		}
	}

?>
