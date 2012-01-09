<?php echo $data['sender']; ?> has edited <?php echo $data['group']; ?>.

To view the group, follow the link below:

<?php echo $html->url('/groups/dashboard/' . $data['group_id'], true); ?>

Thanks,
<?php echo Configure::read('Site.name'); ?>
