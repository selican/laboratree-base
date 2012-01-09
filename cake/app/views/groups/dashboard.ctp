<?php
	echo $javascript->link('extjs/ux/Portal.js');
	echo $javascript->link('extjs/ux/PortalColumn.js');
	echo $javascript->link('extjs/ux/Portlet.js');

	$html->addCrumb('Groups', '/groups/index');
	$html->addCrumb($group['Group']['name'], '/groups/dashboard/' . $group['Group']['id']);
?>
<div id="groups-dashboard"></div>
<script type="text/javascript">
	laboratree.context = <?php echo $javascript->object($context); ?>;
	laboratree.groups.makeDashboard('groups-dashboard', '<?php echo $html->url('/groups/dashboard/' . $group_id . '.json'); ?>');

	Ext.onReady(function() {
		<?php echo $plugin->dashboard($plugins, '/groups/dashboard/' . $group_id . '.json'); ?>
		laboratree.groups.dashboard.portal.doLayout();
	});
</script>
