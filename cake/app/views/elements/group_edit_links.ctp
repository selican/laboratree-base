<?php
	$sections = array(
		'information' => 'Information',
		'picture' => 'Picture',
		'administrators' => 'Administrators',
		'members' => 'Members',
		'projects' => 'Projects',
		'discussions' => 'Discussions',
	);
?>
<div id="group_edit_links">
	<?php
		foreach($sections as $section => $title)
		{
			$selected = ($section == $current) ? ' selected' : '';
			print($html->link($title, "/edit/groups/$section/$group_id", array('class' => "group_edit_link$selected")));
		}
	?>
</div>
