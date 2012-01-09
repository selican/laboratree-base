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

	echo $javascript->object($error);
?>
