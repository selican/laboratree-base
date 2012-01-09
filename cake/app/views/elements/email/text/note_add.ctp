<?php echo $data['sender']; ?> has added a note to <?php echo $data['name']; ?>.

<?php echo $data['note']; ?>

To view the note, follow the link below:

<?php echo $html->url('/notes/view/' . $data['note_id'], true); ?>

Thanks,
<?php echo Configure::read('Site.name'); ?>
