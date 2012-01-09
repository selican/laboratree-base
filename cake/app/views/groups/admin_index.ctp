<?php $html->addCrumb('Admin', '/admin'); ?>
<?php $html->addCrumb('Groups', '/admin/groups/index'); ?>
<div id="groups-div"></div>
<script type="text/javascript">
	laboratree.admin.dashboard.makeGroups('groups-div', '<?php echo $html->url('/admin/groups/index.json'); ?>');
</script>
