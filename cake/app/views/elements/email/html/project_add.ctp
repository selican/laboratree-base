<?php echo $data['sender']; ?> has added you to the <?php echo Configure::read('Site.name'); ?> project "<?php echo $data['project']; ?>".
<br><br>
To view this project's dashboard, follow the link below:
<br><br>
<?php
	$url = $html->url('/projects/dashboard/' . $data['project_id'], true);
	echo $html->link($url, $url);
?>
<br><br>
Thanks,
<br>
<?php echo Configure::read('Site.name'); ?>
