<?php
	$error = array(
		'success' => false,
		'error' => array(
			'access_denied' => array(
				'resource' => $resource,
				'action' => $action,
			),
		),
	);

	echo 'var error = ' . $javascript->object($error) . ';';
?>
