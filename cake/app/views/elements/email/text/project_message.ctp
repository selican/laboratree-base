You have a new message on <?php echo Configure::read('Site.name'); ?> from <?php echo $data['sender']; ?> through "<?php echo $data['project']; ?>".

<?php echo $message['body']; ?>

To view the message, follow the link below:

http://<?php echo Configure::read('Site.domain'); ?>/l/<?php echo $data['inbox_id']; ?>/<?php echo $data['hash']; ?>

Thanks,
<?php echo Configure::read('Site.name'); ?>
