<?php
	if(isset($c) && !empty($c) && file_exists(JS . $c . '.js'))
	{
		echo $javascript->link($c . '.js');
	}

	if(isset($plugins))
	{
		foreach($plugins as $plugin)
		{
			$feature = Inflector::underscore($plugin);

			if(file_exists(APP . DS . 'plugins' . DS . $feature . DS . 'views' . DS . 'elements' . DS . 'jshandler.ctp'))
			{
				echo $this->element('jshandler', array('plugin' => $feature));
			}
		}
	}
?>
