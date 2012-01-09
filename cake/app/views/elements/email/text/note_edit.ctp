<?php echo $data['sender']; ?> has edited a note in <?php echo $data['name']; ?>.

<?php echo $data['note']; ?>

To view the note, follow the link below:

<?php echo $html->url('/notes/view/' . $data['note_id'], true); ?>

Thanks,
<?php echo Configure::read('Site.name'); ?>
