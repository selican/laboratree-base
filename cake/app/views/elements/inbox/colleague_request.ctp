<div class="inbox-request">
	<?php echo $message['Sender']['name'] ; ?> would like to add you as a colleague.
	<table cellspacing="0" class="inbox-request-choices">
		<tr>
			<td><div id="inbox_request_accept"></div></td>
			<td><div id="inbox_request_deny"></div></td>
		</tr>
	</table>
</div>
<script type="text/javascript">
	Ext.onReady(function() {
		var accept = new Ext.Button({
			renderTo: 'inbox_request_accept',
			text: 'Accept',
			handler: function() {
				window.location = '/inbox/accept/<?php echo $message['Inbox']['id']; ?>';
			}
		});	

		var deny = new Ext.Button({
			renderTo: 'inbox_request_deny',
			text: 'Deny',
			handler: function() {
				window.location = '/inbox/deny/<?php echo $message['Inbox']['id']; ?>';
			}
		});
	});
</script>
