<?php $html->addCrumb('Reset Password', '/r/' . $user['User']['id'] . '/' . $hash); ?>
<?php echo $form->create(null, array('url' => '/r/' . $user['User']['id'] . '/' . $hash)); ?>
<table>
	<tr>
		<td><label for="UserPassword1">New Password:</label></td>
		<td><?php print($form->password('User.password1')); ?>
	</tr>
	<tr>
		<td><label for="UserPassword2">New Password (again):</label></td>
		<td><?php print($form->password('User.password2')); ?>
	</tr>
	<tr>
		<td><?php print($form->submit('Change Password', array('style' => 'width: 130px;'))); ?></td>
	</tr>
</table>
<?php echo $form->end(); ?>
