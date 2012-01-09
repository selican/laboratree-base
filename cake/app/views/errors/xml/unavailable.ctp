<?php
	$error = array(
		'success' => false,
		'error' => array(
			'unavailable' => array(
				'resource' => $resource,
				'reason' => $reason,
			),
		),
	);

	echo 'var error = ' . $javascript->object($error) . ';';
?>
