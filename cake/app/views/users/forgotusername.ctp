<?php $html->addCrumb('Users', '/users/index'); ?>
<?php $html->addCrumb('Forgotten Username', '/users/forgotusername'); ?>

<?php echo $form->create('User', array('action' => 'forgotusername')); ?>

<div class="login">
	<table>
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
			<td colspan="2" style="text-align: center;"><?php echo $form->submit('Submit Request', array('class' => 'submitBtn')); ?></td>
		</tr>
	</table>
</div>

<?php echo $form->end(); ?>
