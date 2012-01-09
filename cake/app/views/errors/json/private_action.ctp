<?php
	$error = array(
		'success' => false,
		'error' => array(
			'private_action' => array(
				'controller' => $controller,
				'action' => $action,
			),
		),
	);

	echo $javascript->object($error);
?>
