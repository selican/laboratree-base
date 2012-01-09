<?php
	$html->addCrumb('Login', '/users/login/' . $url);

	echo $form->create('User', array('action' => 'login', 'name' => 'login'));
	echo $form->hidden('User.url', array('value' => $url));
?>
<div class="login">
	<table>
		<tr>
			<td><label for="UserUsername">Username:</label></td>
			<td><?php echo $form->text('User.username'); ?></td>
		</tr>
		<tr>
			<td>
				&nbsp;
			</td>
		</tr>
		<tr>
			<td><label for="UserPassword">Password:</label></td>
			<td><?php echo $form->password('User.password'); ?></td>
		</tr>
		<tr>
			<td>
				&nbsp;
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: center;"><?php echo $form->submit('Login', array('class' => 'submitBtn')); ?></td>
		</tr>
	</table>
</div>

<div align="center">
	<div class="registerMe"><?php echo $html->link('> Register <', '/users/register/' . $url); ?></div>
</div>
<?php echo $form->end(); ?>
<script type="text/javascript">
	Ext.onReady(function() {
		document.login['data[User][username]'].focus();
	});
</script>
