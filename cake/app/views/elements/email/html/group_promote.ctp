<?php echo $data['sender']; ?> has set <?php $data['user']; ?> to <?php echo $data['role']; ?> in <?php echo $data['group']; ?>.
<br><br>
To view the group, follow the link below:
<br><br>
<?php
	$url = $html->url('/groups/dashboard/' . $data['group_id'], true);
	echo $html->link($url, $url);
?>
<br><br>
Thanks,
<br>
<?php echo Configure::read('Site.name'); ?>
