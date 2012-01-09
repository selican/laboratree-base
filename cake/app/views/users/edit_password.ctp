<div class="edit_Info_title">Change Password</div>
<?php echo $this->renderElement('user_edit_links', array('current' => 'edit_password')); ?>
<div class="edit_Info_spacer"></div>
<?php echo $form->create('User', array('url' => '/edit/users/password')); ?>
<br>
<br>
<fieldset>
	<legend>Password</legend>
	<table id="edit_Info">
		<tr>
			<td><label for="UserOldPassword">Old Password:</label></td>
			<td><?php echo $form->password('User.old_password'); ?></td>
			<td><?php echo $form->error('User.old_password'); ?></td>
		</tr>
		<tr>
			<td><label for="UserNewPassword">New Password:</label></td>
			<td><?php echo $form->password('User.new_password'); ?></td>
			<td><?php echo $form->error('User.new_password'); ?></td>
		</tr>
		<tr>
			<td><label for="UserConfirmPassword">Confirm Password:</label></td>
			<td><?php echo $form->password('User.confirm_password'); ?></td>
			<td><?php echo $form->error('User.confirm_password'); ?></td>
		</tr>
		<tr>
			<td colspan="3"><?php echo $form->submit('Save'); ?></td>
		</tr>
	</table>
</fieldset>
<?php echo $form->end(); ?>
