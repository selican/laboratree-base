<?php
	echo $javascript->link('extjs/ux/GroupTabPanel.js');
	echo $javascript->link('extjs/ux/GroupTab.js');

	$html->addCrumb('Groups', '/groups/index');
	$html->addCrumb($group['Group']['name'], '/groups/dashboard/' . $group['Group']['id']); 
	$html->addCrumb('Permissions', '/permissions/group/' . $group['Group']['id']);
?>
<div id="group-permissions"></div>
<script type="text/javascript">
	laboratree.context = <?php echo $javascript->object($context); ?>;
	laboratree.permissions.makeDashboard('group-permissions', 'group', '<?php echo $group_id; ?>');
</script>
