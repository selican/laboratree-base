<?php
	echo $javascript->link('extjs/ux/FileUploadField.js');

	$html->addCrumb('Groups', '/groups/index');
	$html->addCrumb($group['Group']['name'], '/groups/dashboard/' . $group['Group']['id']);
	$html->addCrumb('Edit', '/groups/edit/' . $group['Group']['id']);
?>
<div id="edit_div"></div>
<script type="text/javascript">
	laboratree.context = <?php echo $javascript->object($context); ?>;
	laboratree.groups.makeEdit('edit_div');
</script>
