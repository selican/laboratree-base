<?php
	$error = array(
		'success' => false,
		'error' => array(
			'missing_component_class' => array(
				'controller' => $controller,
				'component' => $component,
			),
		),
	);

	echo 'var error = ' . $javascript->object($error) . ';';
?>
