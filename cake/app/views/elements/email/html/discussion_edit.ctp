<?php echo $data['sender']; ?> has edited a discussion <?php echo $data['type']; ?> in <?php echo $data['name']; ?>.
<br><br>
<?php echo $data['discussion']; ?>
<br><br>
To view the discussion <?php echo $data['type']; ?>, follow the link below:
<br><br>
<?php
	$url = null;

	switch($data['type'])
	{
		case 'category':
			$url = $html->url('/discussions/topics/' . $data['discussion_id'], true);
			break;
		case 'topic':
			$url = $html->url('/discussions/view/' . $data['discussion_id'], true);
			break;
		case 'post':
			$url = $html->url('/discussions/view/' . $data['discussion_id'], true);
			break;
	}

	if(!empty($url))
	{
		echo $html->link($url, $url);
	}
?>
<br><br>
Thanks,
<br>
<?php echo Configure::read('Site.name'); ?>
