<?php
	$error = array(
		'success' => false,
		'error' => array(
			'missing_component_file' => array(
				'component' => $component,
				'file' => $file,
			),
		),
	);

	echo $javascript->object($error);
?>
