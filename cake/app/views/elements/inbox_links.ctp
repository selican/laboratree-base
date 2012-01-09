<?php
	$links = array(
		'Received' => '/inbox/received',
		'Sent' => '/inbox/sent',
		'Trash' => '/inbox/trash',
		'New Message' => '/inbox/send',
		'Address Book' => 'javascript:inbox_addressbook();',
	);

	if($current != 'New Message')
	{
		unset($links['Address Book']);
	}
?>
<div id="inbox_links">
	<?php
		foreach($links as $title => $link)
		{
			$selected = ($title == $current) ? ' inbox_selected' : '';
			echo $html->link($title, $link, array('class' => "inbox_link{$selected}"));
		}
	?>
	<?php echo $form->select('Inbox.action', $actions, null, array('onchange' => "document.getElementById('ReceivedForm').submit();")); ?>
</div>
