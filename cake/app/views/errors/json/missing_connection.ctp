<?php
	$error = array(
		'success' => false,
		'error' => array(
			'missing_connection' => array(
				'model' => $model,
			),
		),
	);

	echo $javascript->object($error);
?>
