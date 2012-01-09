A password reset has been requested for your account on <?php echo Configure::read('Site.name'); ?>
If you did not requeust a password reset, please disregard this message.
To confirm this request and reset your password, follow the link below:

http://<?php echo Configure::read('Site.domain'); ?>/r/<?php echo $user_id; ?>/<?php echo $hash; ?>

Thanks,
<?php echo Configure::read('Site.name'); ?>
