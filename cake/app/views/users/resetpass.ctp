<?php $html->addCrumb('Users', '/users/index'); ?>
<?php $html->addCrumb('Reset Password', '/users/resetpass'); ?>

<?php echo $form->create('User', array('action' => 'resetpass')); ?>

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
			<td><label for="UserEmail">Email Address:</label></td>
			<td><?php echo $form->text('User.email'); ?></td>
		</tr>
		<tr>
			<td>
				&nbsp;
			</td>
		</tr>	
		<tr>
			<td colspan="2" style="text-align: center;"><?php echo $form->submit('Request Password Reset', array('class' => 'submitBtn')); ?></td>
		</tr>
	</table>
</div>

<?php echo $form->end(); ?>
