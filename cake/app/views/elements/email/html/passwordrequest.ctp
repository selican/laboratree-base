A password reset has been requested for your account on <?php echo Configure::read('Site.name'); ?>.
<br><br>
If you did not requeust a password reset, please disregard this message.
<br><br>
To confirm this request and reset your password, follow the link below:
<br><br>
<?php
	$url = $html->url('/r/' . $user_id . '/' . $hash, true);
	echo $html->link($url, $url);
?>
<br><br>
Thanks,
<br>
<?php echo Configure::read('Site.name'); ?>
