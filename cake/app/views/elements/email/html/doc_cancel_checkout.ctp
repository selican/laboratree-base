<?php echo $data['sender']; ?> has cancelled a document check out in <?php echo $data['name']; ?>.
<br><br>
<?php echo $data['document']; ?>
<br><br>
To view the document, follow the link below:
<br><br>
<?php
	$url = $html->url('/docs/view/' . $data['doc_id'], true);
	echo $html->link($url, $url);
?>
<br><br>
Thanks,
<br>
<?php echo Configure::read('Site.name'); ?>
