You have a new message on <?php echo Configure::read('Site.name'); ?> from <?php echo $data['sender']; ?>.

<?php echo $message['body']; ?>

To view the message, follow the link below:

http://<?php echo Configure::read('Site.domain'); ?>/l/<?php echo $data['inbox_id']; ?>/<?php echo $data['hash']; ?>

If you are not a <?php echo Configure::read('Site.name'); ?> member, join us. To register, follow the link below:

http://<?php echo Configure::read('Site.domain'); ?>/l/<?php echo $data['inbox_id']; ?>/<?php echo $data['hash']; ?>/register

Thanks,
<?php echo Configure::read('Site.name'); ?>
