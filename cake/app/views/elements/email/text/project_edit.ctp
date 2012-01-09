<?php echo $data['sender']; ?> has edited <?php echo $data['project']; ?>.

To view the project, follow the link below:

<?php echo $html->url('/projects/dashboard/' . $data['project_id'], true); ?>

Thanks,
<?php echo Configure::read('Site.name'); ?>
