laboratree.links = {
	'base': '<?php echo Configure::read('Site.full'); ?>',
<?php
	foreach($links as $controller => $actions)
	{
		echo "\t'$controller': {\n";
		foreach($actions as $action => $arguments)
		{
			echo "\t\t'$action': '" . Configure::read('Site.full') . "$controller/$action";
			for($i = 0; $i < count($arguments); $i++)
			{
				echo '/{' . $i . '}';
			}
			echo "',\n";
		}
		echo "\t},\n";
	}
?>
};
