<?php
	echo $javascript->link('extjs/ux/Recaptcha.js');
?>
<div id="register-div"></div>
<script type="text/javascript">
	laboratree.users.makeRegister('register-div', '<?php echo $html->url('/users/register.json'); ?>');
</script>
