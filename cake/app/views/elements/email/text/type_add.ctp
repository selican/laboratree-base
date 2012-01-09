<?php echo $data['sender']; ?> has added a document type to <?php echo $data['name']; ?>.

<?php echo $data['type']; ?>

To view the document type, follow the link below:

<?php echo $html->url('/types/view/' . $data['type_id'], true); ?>

Thanks,
<?php echo Configure::read('Site.name'); ?>
