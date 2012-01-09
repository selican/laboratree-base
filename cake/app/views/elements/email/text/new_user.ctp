<?php echo $data['sender']; ?> has created an account for you on <?php echo Configure::read('Site.name'); ?>.

Please verify your email address by following the link below:

http://<?php echo Configure::read('Site.domain'); ?>/v/<?php echo $data['user_id']; ?>/<?php echo $data['user_hash']; ?>/

You will be redirected to the login screen upon verification, and you may log in with the following temporary username and password:

Username: <?php echo $data['username']; ?>
Password: <?php echo $data['password']; ?>

Thanks,
<?php echo Configure::read('Site.name'); ?>
