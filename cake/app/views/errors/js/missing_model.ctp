<?php
	$error = array(
		'success' => false,
		'error' => array(
			'missing_model' => array(
				'model' => $model,
			),
		),
	);

	echo 'var error = ' . $javascript->object($error) . ';';
?>
