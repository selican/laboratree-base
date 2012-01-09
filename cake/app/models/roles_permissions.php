<?php
class RolesPermissions extends AppModel
{
	var $name = 'RolesPermissions';

	var $belongsTo = array(
		'Role' => array(
			'className' => 'Role',
			'foreignKey' => 'role_id',
		),
		'Perm' => array(
			'className' => 'Perm',
			'foreignKey' => 'permission_id',
		),
	);
}
?>
