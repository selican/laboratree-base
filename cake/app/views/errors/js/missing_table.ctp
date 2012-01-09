<?php
	$error = array(
		'success' => false,
		'error' => array(
			'missing_table' => array(
				'table' => $table,
				'model' => $model,
			),
		),
	);

	echo 'var error = ' . $javascript->object($error) . ';';
?>
