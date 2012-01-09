<?php echo $data['sender']; ?> has added a link to <?php echo $data['name']; ?>.
<br><br>
<?php echo $data['label']; ?>
<br>
<?php echo $html->link($data['link'], $data['link']); ?>
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
