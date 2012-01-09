<?php echo $data['sender']; ?> has edited a document type in <?php echo $data['name']; ?>.
<br><br>
<?php echo $data['type']; ?>
<br><br>
To view the document type, follow the link below:
<br><br>
<?php
	$url = $html->url('/types/view/' . $data['type_id'], true);
	echo $html->link($url, $url);
?>
<br><br>
Thanks,
<br>
<?php echo Configure::read('Site.name'); ?>
