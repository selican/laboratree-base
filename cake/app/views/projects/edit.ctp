<?php
	$html->addCrumb('Groups', '/groups/index'); 
	$html->addCrumb($group['Group']['name'], '/groups/dashboard/' . $group_id);
	$html->addCrumb('Projects', '/projects/group/' . $group_id); 
	$html->addCrumb('Edit Project', '/projects/edit/' . $project['Project']['id']);

	echo $javascript->link('extjs/ux/FileUploadField.js');
?>
<div id="edit_div"></div>
<script type="text/javascript">
	laboratree.context = <?php echo $javascript->object($context); ?>;
	laboratree.projects.makeEdit('edit_div');
</script>
