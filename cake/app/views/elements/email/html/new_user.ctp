<?php echo $data['sender']; ?> has created an account for you on <?php echo Configure::read('Site.name'); ?>.
<br><br>
Please verify your email address by following the link below:
<?php
	$url = $html->url('/v/' . $data['user_id'] . '/' . $data['user_hash'] . '/', true);
	echo $html->url($url, true);
?>
<br><br>
You will be redirected to the login screen upon verification, and you may log in with the following temporary username and password:
<br><br>
Username: <?php echo $data['username']; ?>
<br>
Password: <?php echo $data['password']; ?>
<br><br>
Thanks,
<br>
<?php echo Configure::read('Site.name'); ?>
