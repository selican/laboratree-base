<?php echo $data['sender']; ?> has edited a link in <?php echo $data['name']; ?>.
<br><br>
<?php echo $data['label']; ?>
<br><br>
<?php echo $data['link']; ?>
<br><br>
To view the link information, follow the link below:
<br><br>
<?php
	$url = $html->url('/urls/view/' . $data['url_id'], true);
	echo $html->link($url, $url);
?>
<br><br>
Thanks,
<br>
<?php echo Configure::read('Site.name'); ?>
