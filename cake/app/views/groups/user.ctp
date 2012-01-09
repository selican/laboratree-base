<?php
	$html->addCrumb('Groups', '/groups/index');
?>
<div id="groups-div"></div>
<?php
	echo $javascript->link('extjs/ux/Portal.js');
	echo $javascript->link('extjs/ux/PortalColumn.js');
	echo $javascript->link('extjs/ux/Portlet.js');
?>
<script type="text/javascript">
	laboratree.context = <?php echo $javascript->object($context); ?>;
	laboratree.groups.makeList('groups-div', '<?php echo 'Groups - ' . addslashes($session->read('Auth.User.name')); ?>', '<?php echo $html->url('/groups/user.json'); ?>');
</script>
