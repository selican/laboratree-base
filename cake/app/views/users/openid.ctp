<?php
	$html->addCrumb('OpenID Login', '/users/openid/');
	echo $form->create('User', array('action' => 'openid'));
?>
<div class="openid">
	<table>
		<tr>
			<td><label for="User.openid">OpenID:</label></td>
			<td><?php echo $form->text('User.openid'); ?></td>
		</tr>
		<tr>
			<td>
				&nbsp;
			</td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 5px 0; font-weight: bold;">Remember Me:&nbsp;&nbsp;<?php echo $form->checkbox('User.remember'); ?></td>
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
<?php echo $form->end(); ?>

