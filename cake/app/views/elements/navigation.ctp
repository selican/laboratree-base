<?php
	$c = (isset($c)) ? $c : '';
	$a = (isset($a)) ? $a : '';
	$p = (isset($p)) ? $p : '';

	$mailcount = (isset($mailcount)) ? $mailcount : 0;

	$page = '';

	if($c == 'pages')
	{
		if($a == 'display')
		{
			if($p == 'home')
			{
				$page = 'home';
			}
		}
	}
	else if($c == 'admin')
	{
		$page = 'admin';
	}

?>

<span class="navBar">
	<?php if(!$session->check('Auth.User')): ?>
	<ul>
		<li class="navSplit"><?php echo $html->link('Register', '/users/register'); ?></li>
		<li class="navSplit"><?php echo $html->link('Login', '/users/login');?></li>
		<li class="navSplit"><?php echo $html->link('Forgot Password', '/users/resetpass'); ?></li>
		<li class="navSplit"><?php echo $html->link('Forgot Username', '/users/forgotusername'); ?></li>
		<li class="navEnd"><?php echo $html->link('Help', '/help/site'); ?></li>
	</ul>
	<?php else: ?>
	<ul>
		<li class="nav_name"><?php echo $html->link($session->read('Auth.User.name'), '/users/dashboard/'); ?></li>
		<li class="nav_profile"><?php echo $html->link('Account', '/users/account'); ?></li>
		<li class="nav_inbox"><?php echo $html->link("Inbox ($mailcount)", '/inbox'); ?></li>
		<li class="navSplit"><?php echo $html->link('Logout', '/users/logout'); ?></li>
		<li class="navEnd"><?php echo $html->link('Help', '/help'); ?></li>
	</ul>
	<?php endif; ?>
</span>
