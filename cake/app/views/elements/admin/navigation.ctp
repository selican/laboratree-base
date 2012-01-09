<?php
	$mailcount = (isset($mailcount)) ? $mailcount : 0;
?>
<div class="navBar">
	<ul>
		<li><?php echo $html->link($session->read('Auth.User.name'), '/users/dashboard'); ?></li>
		<li><?php echo $html->link('Account', '/users/account'); ?></li>
		<li><?php echo $html->link("Inbox ($mailcount)", '/inbox'); ?></li>
		<li><?php echo $html->link('Admin', '/admin'); ?></li>
		<li><?php echo $html->link('Logout', '/admin/users/logout'); ?></li>
	</ul>
</div>
<div class="adminMode">ADMINISTRATOR MODE</div>
