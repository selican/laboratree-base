<?php echo $data['sender']; ?> has added a document to <?php echo $data['name']; ?>.

<?php echo $data['document']; ?>

To view the document, follow the link below:

<?php echo $html->url('/docs/view/' . $data['doc_id'], true); ?>

Thanks,
<?php echo Configure::read('Site.name'); ?>
