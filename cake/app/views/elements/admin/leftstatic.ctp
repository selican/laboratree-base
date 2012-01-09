<div class="navtitle"></div>
<div class="linkboxTop">
	<ul>
		<li><?php echo $html->link('Home', '/'); ?></li>
	</ul>
</div>
<?php if($session->check('Auth.User') && $session->read('Auth.User.id')): ?>
<div class="navtitle">Admin</div>
<div class="linkbox">
	<?php echo $html->link('Users', '/admin/users'); ?><br />
	<?php echo $html->link('Groups', '/admin/groups'); ?><br />
	<?php echo $html->link('Projects', '/admin/projects'); ?><br />
</div>
<?php endif; ?>
