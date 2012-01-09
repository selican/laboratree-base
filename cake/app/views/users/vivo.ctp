<?php $html->addCrumb('Users', '/users/index'); ?>
<?php $html->addCrumb($session->read('Auth.User.name'), '/users/dashboard/' . $session->read('Auth.User.id')); ?>
<?php $html->addCrumb('Import VIVO Profile', '/users/vivo'); ?>
<div id="vivo-div"></div>
<script type="text/javascript">
	laboratree.users.makeVivo('vivo-div', '<?php echo $html->url('/users/vivo.json'); ?>');
</script>
