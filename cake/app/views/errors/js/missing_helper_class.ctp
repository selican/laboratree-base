<?php
	$error = array(
		'success' => false,
		'error' => array(
			'missing_helper_class' => array(
				'helper_class' => $helperClass,
				'file' => $file,
			),
		),
	);

	echo 'var error = ' . $javascript->object($error) . ';';
?>
