<?php
	echo $javascript->link('extjs/ux/Portal.js');
	echo $javascript->link('extjs/ux/PortalColumn.js');
	echo $javascript->link('extjs/ux/Portlet.js');
	echo $javascript->link('extjs/ux/ColumnNodeUI.js');

	$html->addCrumb('Users', '/users/index');
	$html->addCrumb($session->read('Auth.User.name'), '/users/dashboard');
?>
<div id="users-dashboard"></div>
<script type="text/javascript">
	laboratree.context = <?php echo $javascript->object($context); ?>;
	laboratree.users.makeDashboard('users-dashboard', '<?php echo $html->url('/users/dashboard.json'); ?>', '<?php echo $session->read('Auth.User.id'); ?>');

	Ext.onReady(function() {
		<?php echo $plugin->dashboard($plugins, '/users/dashboard.json'); ?>
		laboratree.users.dashboard.portal.doLayout();
	});
</script>
