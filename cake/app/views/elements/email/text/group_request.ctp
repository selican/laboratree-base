<?php echo $data['sender']; ?> has request to join the <?php echo Configure::read('Site.name'); ?> group "<?php echo $data['group']; ?>".

To view the message, follow the link below:

http://<?php echo Configure::read('Site.domain'); ?>/l/<?php echo $data['inbox_id']; ?>/<?php echo $data['hash']; ?>

Thanks,
<?php echo Configure::read('Site.name'); ?>
