
<div class="linkboxTop">
	<ul>
		<li><?php echo $html->link('My Dashboard', '/'); ?></li>
		<?php if($session->check('Auth.User')): ?>
		<li><?php echo $html->link('Inbox', '/inbox/received'); ?></li>
		<?php if($session->read('Auth.User.admin')): ?>
		<li><?php echo $html->link('Admin Mode', '/admin', array('style' => 'color: #fea406;')); ?></li>
		<?php endif; ?>
		<?php endif; ?>
	</ul>
</div>
