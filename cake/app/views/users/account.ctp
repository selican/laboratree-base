<?php
	echo $javascript->link('extjs/ux/FileUploadField.js');

	$html->addCrumb('Users', '/users/index');
	$html->addCrumb($session->read('Auth.User.name'), '/users/dashboard/' . $session->read('Auth.User.id'));
	$html->addCrumb('Account', '/users/account');
?>
<div id="account_div"></div>
<script type="text/javascript">
	laboratree.users.makeEdit('account_div', '<?php echo $section; ?>');
</script>
