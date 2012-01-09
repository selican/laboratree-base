<?php $html->addCrumb('Admin', '/admin'); ?>
<?php $html->addCrumb('Users', '/admin/users/index'); ?>
<div id="users-div"></div>
<script type="text/javascript">
	laboratree.admin.dashboard.makeUsers('users-div', '<?php echo $html->url('/admin/users/index.json'); ?>');
</script>
