Please verify your email address by following the link below:
<br><br>
<?php
	$link = $html->url('/v/' . $user_id . '/' . $hash . '/' . $url, true);
	echo $html->link($link, $link);
?>
<br><br>
You will be redirected to the login screen upon verification.
<br>
Thanks,
<br>
<?php echo Configure::read('Site.name'); ?>
