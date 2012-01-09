<?php if($session->check('Auth.User')): ?>
<?php
	$params = array(
		'_dc' => uniqid()
	);

	$connect = Configure::read('Chat.connectUrl') . '?' . http_build_query($params, '', '&amp;');
?>
<div class="navtitle" id="chat"></div>
<script type="text/javascript">
	laboratree.chat.type = 'window';
	laboratree.chat.priority = 0;
</script>
<div id="connect"><script type="text/javascript" src="<?php echo $connect; ?>"></script></div>
<?php endif; ?>
