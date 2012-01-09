<?php $html->addCrumb('Groups', '/groups/index'); ?>
<?php $html->addCrumb($group['Group']['name'], '/groups/dashboard/' . $group['Group']['id']); ?>
<?php $html->addCrumb('Members', '/groups/members/' . $group['Group']['id']); ?>
<div id="members-div"></div>
<script type="text/javascript">
	laboratree.context = <?php echo $javascript->object($context); ?>;
	laboratree.groups.makeMembers('members-div', <?php echo $group['Group']['id']; ?>);
</script>
