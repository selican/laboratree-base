<?php
	$error = array(
		'success' => false,
		'error' => array(
			'missing_view' => array(
				'controller' => $controller,
				'action' => $action,
				'file' => $file,
			),
		),
	);

	echo $javascript->object($error);
?>
