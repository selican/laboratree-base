<?php
class NavigationHelper extends AppHelper
{
	function substitute(&$navigation, &$vars)
	{
		if(isset($navigation['tabs']) && !empty($navigation['tabs']))
		{
			for($i = 0; $i < sizeof($navigation['tabs']); $i++)
			{
				if(!empty($navigation['tabs'][$i]['Navigation']['url']))
				{
					$navigation['tabs'][$i]['Navigation']['url'] = $this->urlsub($navigation['tabs'][$i]['Navigation']['url'], $vars);
				}
			}
		}

		if(isset($navigation['subtabs']) && !empty($navigation['subtabs']))
		{
			for($i = 0; $i < sizeof($navigation['subtabs']); $i++)
			{
				if(!empty($navigation['subtabs'][$i]['Navigation']['url']))
				{
					$navigation['subtabs'][$i]['Navigation']['url'] = $this->urlsub($navigation['subtabs'][$i]['Navigation']['url'], $vars);
				}
			}
		}
	}

	function urlsub($url, &$vars)
	{
		if(!is_array($vars))
		{
			return $url;
		}

		foreach($vars as $key => $value)
		{
			if(is_array($value))
			{
				continue;
			}

			$url = str_replace('{' . $key . '}', $value, $url);
		}	

		return $url;
	}
}
?>
