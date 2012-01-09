<?php
	$error = array(
		'success' => false,
		'error' => array(
			'invalid_field' => array(
				'field' => $field,
			),
		),
	);

	echo $javascript->object($error);
?>
