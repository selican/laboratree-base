<?php //TODO: Convert ?>
<?php echo nl2br($message); ?>
<br><br>
<img src="<?php echo $html->url('http://labimg.selican.com/img/email/LTbar.png', true) ?>" />
<br><br>
To view the message, follow the link below:
<br><br>
<?php
	$url = $html->url('/l/' . $data['inbox_id'] . '/' . $data['hash'], true);
	echo $html->link($url, $url);
?>
<br><br>
<img src="<?php echo $html->url('http://labimg.selican.com/img/email/LTbar.png', true) ?>" />
<br><br>
Thanks,
<br>
<?php echo Configure::read('Site.name'); ?>
