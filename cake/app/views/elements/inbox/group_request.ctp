<div id="inbox-choices-div"></div>
<script type="text/javascript">
	<?php
		$msg = addslashes($message['Sender']['name'] . ' has requested to join the group ' . $group['Group']['name']);
	?>
	laboratree.inbox.makeAcceptDeny('inbox-choices-div', '<?php echo $message['Inbox']['id']; ?>', '<?php echo $msg; ?>');
</script>
