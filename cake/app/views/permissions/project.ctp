<?php
	echo $javascript->link('extjs/ux/GroupTabPanel.js');
	echo $javascript->link('extjs/ux/GroupTab.js');

	$html->addCrumb('Groups', '/groups/index'); 
	$html->addCrumb($group['Group']['name'], '/groups/dashboard/' . $group_id);
	$html->addCrumb('Projects', '/projects/group/' . $group_id);
	$html->addCrumb($project['Project']['name'], '/projects/dashboard/' . $project['Project']['id']); 
	$html->addCrumb('Permissions', '/permissions/project/' . $project['Project']['id']);
?>
<div id="project-permissions"></div>
<script type="text/javascript">
	laboratree.context = <?php echo $javascript->object($context); ?>;
	laboratree.permissions.makeDashboard('project-permissions', 'project', '<?php echo $project_id; ?>');
</script>
