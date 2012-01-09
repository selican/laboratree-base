<?php
	$html->addCrumb('Groups', '/groups/index'); 
	$html->addCrumb($group['Group']['name'], '/groups/dashboard/' . $group_id);
	$html->addCrumb('Projects', '/projects/group/' . $group_id); 
?>
<div id="projects-div"></div>
<script type="text/javascript">
	laboratree.context = <?php echo $javascript->object($context); ?>;
	laboratree.projects.makeList('projects-div', 'Projects - <?php echo addslashes($group['Group']['name']); ?>', '<?php echo $html->url('/projects/group/' . $group['Group']['id'] . '.json'); ?>');
</script>
