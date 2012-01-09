<?php 
	$html->addCrumb('Groups', '/groups/index'); 
	$html->addCrumb($group['Group']['name'], '/groups/dashboard/' . $group_id);
	$html->addCrumb('Projects', '/projects/group/' . $group_id); 
	$html->addCrumb('Create Project', '/projects/create/' . $group_id);
?>
<div id="projects-create-div"></div>
<script type="text/javascript">
	laboratree.context = <?php echo $javascript->object($context); ?>;
	laboratree.projects.makeCreate('projects-create-div', '<?php echo $html->url('/projects/create/' . $group_id); ?>');
</script>
