The password for your account has been reset. Your new password is:
<br><br>
<?php echo $newpass; ?>
<br><br>
You will be required to change it the next time you login.
<br><br>
To login, follow the link below:
<br><br>
<?php
	$url = $html->url('/users/login', true);
	echo $html->link($url, $url);
?>
<br><br>
Thanks,
<br>
<?php echo Configure::read('Site.name'); ?>
