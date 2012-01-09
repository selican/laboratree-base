<?php
	$sections = array(
		'information' => 'Information',
		'picture' => 'Picture',
		'administrators' => 'Administrators',
		'members' => 'Members',
		'discussions' => 'Discussions',
	);
?>
<div id="group_edit_links">
	<?php
		foreach($sections as $section => $title)
		{
			$selected = ($section == $current) ? ' selected' : '';
			print($html->link($title, "/edit/projects/$section/$project_id", array('class' => "projects_edit_link$selected")));
		}
	?>
</div>
