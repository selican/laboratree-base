<?php
	$error = array(
		'success' => false,
		'error' => array(
			'missing_field' => array(
				'field' => $field,
			),
		),
	);

	echo 'var error = ' . $javascript->object($error) . ';';
?>
