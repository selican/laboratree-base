<?php echo $data['sender']; ?> has edited a link in <?php echo $data['name']; ?>.

<?php echo $data['label']; ?>
<?php echo $data['link']; ?>

To view the link information, follow the link below:

<?php echo $html->url('/urls/view/' . $data['url_id'], true); ?>

Thanks,
<?php echo Configure::read('Site.name'); ?>
