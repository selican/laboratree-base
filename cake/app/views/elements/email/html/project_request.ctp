<?php echo $data['sender']; ?> has requested to join the <?php echo Configure::read('Site.name'); ?> project "<?php echo $data['project']; ?>".
<br><br>
To view the message, follow the link below:
<br><br>
<?php
	$url = $html->url('/l/' . $data['inbox_id'] . '/' . $data['hash'], true);
	echo $html->link($url, $url);
?>
<br><br>
Thanks,
<br>
<?php echo Configure::read('Site.name'); ?>
