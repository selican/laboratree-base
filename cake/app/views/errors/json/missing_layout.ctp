<?php
	$error = array(
		'success' => false,
		'error' => array(
			'missing_layout' => array(
				'file' => $file,
			),
		),
	);

	echo $javascript->object($error);
?>
