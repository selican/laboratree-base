<?php
	$error = array(
		'success' => false,
		'error' => array(
			'internal_error' => array(
				'resource' => $resource,
				'action' => $action,
			),
		),
	);

	echo 'var error = ' . $javascript->object($error) . ';';
?>
