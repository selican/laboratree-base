<?php echo $data['sender']; ?> has added you to the <?php echo Configure::read('Site.name'); ?> group "<?php echo $data['group']; ?>".

To view this group's dashboard, follow the link below:

http://<?php echo Configure::read('Site.domain'); ?>/groups/dashboard/<?php echo $data['group_id']; ?>

Thanks,
<?php echo Configure::read('Site.name'); ?>
