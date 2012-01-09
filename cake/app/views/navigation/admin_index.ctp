<?php
	$html->addCrumb('Admin', '/admin');
	$html->addCrumb('Navigation', '/admin/navigation/index');

	echo $javascript->link('extjs/ux/ColumnNodeUI.js');
	echo $javascript->link('extjs/ux/FileUploadField.js');
?>
<div id="navigation-div" class="ext-style"></div>
<script type="text/javascript">
	var data_url = '<?php echo $html->url('/admin/navigation/index.json'); ?>';
	var reparent_url = '<?php echo $html->url('/admin/navigation/reparent.json'); ?>';
	var reorder_url = '<?php echo $html->url('/admin/navigation/reorder.json'); ?>';

	laboratree.admin.dashboard.makeNavigation('navigation-div', data_url, reparent_url, reorder_url);
</script>
