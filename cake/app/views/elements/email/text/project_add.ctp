<?php echo $data['sender']; ?> has added you to the <?php echo Configure::read('Site.name'); ?> project "<?php echo $data['project']; ?>".

To view this project's dashboard, follow the link below:

http://<?php echo Configure::read('Site.domain'); ?>/projects/dashboard/<?php echo $data['project_id']; ?>

Thanks,
<?php echo Configure::read('Site.name'); ?>
