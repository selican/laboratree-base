<?php $html->addCrumb('Users', '/users/index'); ?>
<?php $html->addCrumb($session->read('Auth.User.name'), '/users/dashboard/' . $session->read('Auth.User.id')); ?>
<?php $html->addCrumb('Account', '/users/account'); ?>
<?php $html->addCrumb('Change Password', '/users/changepass'); ?>

<?php echo $form->create('User', array('action' => 'changepass')); ?>

<div class="changePass">
	<table>
		<tr>
			<td><label for="UserCurrent">Current Password:</label></td>
			<td><?php print($form->password("User.current")); ?>
		</tr>
		<tr>
			<td>
				&nbsp;
			</td>
		</tr>
		<tr>
			<td><label for="UserPassword1">New Password:</label></td>
			<td><?php print($form->password("User.password1")); ?>
		</tr>
		<tr>
			<td>
				&nbsp;
			</td>
		</tr>	
		<tr>
			<td><label for="UserPassword2">New Password (again):</label></td>
			<td><?php print($form->password("User.password2")); ?>
		</tr>
		<tr>
			<td>
				&nbsp;
			</td>
		</tr>	
		<tr>
			<td colspan="2" style="text-align: center;"><?php echo $form->submit('Change Password', array('class' => 'submitBtn')); ?></td>
		</tr>

	</table>
</div>

<?php echo $form->end(); ?>
