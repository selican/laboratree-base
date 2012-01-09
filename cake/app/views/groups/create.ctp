<?php $html->addCrumb('Groups', '/groups/index'); ?>
<?php $html->addCrumb('Create', '/groups/create'); ?>
<div id="groups-create-div"></div>
<script type="text/javascript">
	laboratree.groups.makeCreate('groups-create-div', '<?php echo $html->url('/groups/create'); ?>');
</script>
