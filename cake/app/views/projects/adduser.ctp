<?php
	$html->addCrumb('Groups', '/groups/index'); 
	$html->addCrumb($group['Group']['name'], '/groups/dashboard/' . $group_id);
	$html->addCrumb('Projects', '/projects/group/' . $group_id); 
	$html->addCrumb($project['Project']['name'], '/projects/dashboard/' . $project['Project']['id']);
	$html->addCrumb('Members', '/projects/members/' . $project['Project']['id']);
	$html->addCrumb('Add Users', '/projects/adduser/' . $project['Project']['id']);
?>
<div id="adduser-div"></div>
<script type="text/javascript">
	laboratree.context = <?php echo $javascript->object($context); ?>;
	laboratree.projects.makeAddUser('adduser-div', '<?php echo $html->url('/projects/adduser/' . $project['Project']['id'] . '.json'); ?>');
</script>
