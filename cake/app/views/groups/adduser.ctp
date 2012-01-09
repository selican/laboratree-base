<?php
	$html->addCrumb('Groups', '/groups/index');
	$html->addCrumb($group['Group']['name'], '/groups/dashboard/' . $group['Group']['id']);
	$html->addCrumb('Members', '/groups/members/' . $group['Group']['id']);
	$html->addCrumb('Add Users', '/groups/adduser/' . $group['Group']['id']);
?>
<div id="adduser-div"></div>
<script type="text/javascript">
	laboratree.context = <?php echo $javascript->object($context); ?>;
	laboratree.groups.makeAddUser('adduser-div', '<?php echo $html->url('/groups/adduser/' . $group['Group']['id'] . '.json'); ?>');
</script>
