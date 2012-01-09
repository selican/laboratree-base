<?php
	$html->addCrumb('Groups', '/groups/index');
	$html->addCrumb($group['Group']['name'], '/groups/dashboard/' . $group['Group']['id']);
	$html->addCrumb('Group Roles', '/groups/roles/' . $group['Group']['id']);
?>
<div id="roles-div"></div>
<script type="text/javascript">
	laboratree.groups.makeRoles('roles-div', '<?php echo $html->url('/groups/roles/' . $group['Group']['id']); ?>', '<?php echo $group_id; ?>');
</script>
