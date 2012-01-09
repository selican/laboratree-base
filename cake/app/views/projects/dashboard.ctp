<?php
	echo $javascript->link('extjs/ux/Portal.js');
	echo $javascript->link('extjs/ux/PortalColumn.js');
	echo $javascript->link('extjs/ux/Portlet.js');

	$html->addCrumb('Groups', '/groups/index'); 
	$html->addCrumb($group['Group']['name'], '/groups/dashboard/' . $group_id);
	$html->addCrumb('Projects', '/projects/group/' . $group_id); 
	$html->addCrumb($project['Project']['name'], '/projects/dashboard/' . $project_id);
?>
<div id="projects-dashboard"></div>
<script type="text/javascript">
	laboratree.context = <?php echo $javascript->object($context); ?>;
	laboratree.projects.makeDashboard('projects-dashboard', '<?php echo $html->url('/projects/dashboard/' . $project_id . '.json'); ?>');

	Ext.onReady(function() {
		<?php echo $plugin->dashboard($plugins, '/projects/dashboard/' . $project_id . '.json'); ?>
		laboratree.projects.dashboard.portal.doLayout();
	});
</script>
