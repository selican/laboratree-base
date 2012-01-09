<?php
	$error = array(
		'success' => false,
		'error' => array(
			'missing_action' => array(
				'controller' => $controller,
				'action' => $action,
			),
		),
	);

	echo $javascript->object($error);
?>
