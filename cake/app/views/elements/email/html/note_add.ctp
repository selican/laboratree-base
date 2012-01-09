<?php echo $data['sender']; ?> has added a note to <?php echo $data['name']; ?>.
<br><br>
<?php echo $data['note']; ?>
<br><br>
To view the note, follow the link below:
<br><br>
<?php
	$url = $html->url('/notes/view/' . $data['note_id'], true);
	echo $html->link($url, $url);
?>
<br><br>
Thanks,
<br>
<?php echo Configure::read('Site.name'); ?>
