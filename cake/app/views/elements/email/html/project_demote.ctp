<?php echo $data['sender']; ?> has set <?php $data['user']; ?> to <?php echo $data['role']; ?> in <?php echo $data['project']; ?>.
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
