<?php echo $data['user']; ?> has joined <?php echo $data['project']; ?>.
<br><br>
To view the project, follow the link below:
<br><br>
<?php
	$url = $html->url('/projects/dashboard/' . $data['project_id'], true);
	echo $html->link($url, $url);
?>
<br><br>
Thanks,
<br>
<?php echo Configure::read('Site.name'); ?>
