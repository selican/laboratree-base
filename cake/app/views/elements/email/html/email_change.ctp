<?php //TODO: Convert ?>
Please verify you changed your email address by clicking the link below:
<br><br>
<?php
	$url = $html->url('http://' . Configure::read('Site.domain') . '/v/' . $user_id . '/' . $hash);
	echo $html->link($url, $url);
?>
<br><br>
You will be redirected to the login screen upon verification.
<br>
If you feel you have received this email in error, or you did not change you email address, please contact <a href="mailto:support@selican.com">support@selican.com</a>
<br><br>
Thanks,
<br>
<?php echo Configure::read('Site.name'); ?>
