<?php echo $data['sender']; ?> has added you to the <?php echo Configure::read('Site.name'); ?> group "<?php echo $data['group']; ?>".
<br><br>
To view this group's dashboard, follow the link below:
<br><br>
<?php
	$url = $html->url('/groups/dashboard/' . $data['group_id'], true);
	echo $html->link($url, $url);
?>
<br><br>
Thanks,
<br>
<?php echo Configure::read('Site.name'); ?>
