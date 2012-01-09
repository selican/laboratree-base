<?php echo $data['sender']; ?> has checked out a document in <?php echo $data['name']; ?>.

<?php echo $data['document']; ?>

To view the document, follow the link below:

<?php echo $html->url('/docs/view/' . $data['doc_id'], true); ?>

Thanks,
<?php echo Configure::read('Site.name'); ?>
