<?php
	function navigate($navigation, &$html)
	{
		foreach($navigation as $item)
		{
			if(!empty($item['children']))
			{
				echo '<div class="sideNavBox"><div class="sideNavTop"><div class="sideNavTitle">';
			}

			if(!empty($item['Navigation']['url']))
			{
				echo $html->link($item['Navigation']['title'], $item['Navigation']['url']);
			}
			else
			{
				echo $item['Navigation']['title'];
			}

			if(!empty($item['children']))
			{
				echo '</div></div>';
				echo '<div class="sideNavMid">';
				navigate($item['children'], $html);
				echo '</div><div class="sideNavBot"></div></div>';
			}
		}
	}

	if(!isset($pagerole))
	{
		$pagerole = 'user';
	}

	$action = array(
		'controller' => 'navigation',
		'action' => 'tree',
	);

	$pass = array($c, $a, $pagerole);
	$navigation = $this->requestAction(array(
		'controller' => 'navigation',
		'action' => 'tree',
	), array(
		'pass' => array(
			$c,
			$a,
			$pagerole,
		),
	));

	navigate($navigation, $html);
?>
