<?php
	$error = array(
		'success' => false,
		'error' => array(
			'missing_controller' => array(
				'controller' => $controller,
			),
		),
	);

	echo $javascript->object($error);
?>
