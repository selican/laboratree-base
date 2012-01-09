<?php echo $data['sender']; ?> has added a discussion <?php echo $data['type']; ?> to <?php echo $data['name']; ?>.

<?php echo $data['discussion']; ?>

To view the discussion <?php echo $data['type']; ?>, follow the link below:

<?php
	switch($data['type'])
	{
		case 'category':
			echo $html->url('/discussions/topics/' . $data['discussion_id'], true);
			break;
		case 'topic':
			echo $html->url('/discussions/view/' . $data['discussion_id'], true);
			break;
		case 'post':
			echo $html->url('/discussions/view/' . $data['discussion_id'], true);
			break;
	}
?>

Thanks,
<?php echo Configure::read('Site.name'); ?>
