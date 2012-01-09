<?php
	$html->addCrumb('Help', '/help/site');
	$html->addCrumb('Help - Table of Contents', '/help/site/getstarted');
?>

<div class="help">
	<h2>Help - Table of Contents</h2>
	<div class="col-mask three-col">
		<div class="mid-col">
			<div class="left-col">
				<div class="col1">
					<h3>User Pages</h3>
					<ul>
						<li><?php echo $html->link('User Dashboard', '/help/users/dashboard'); ?></li>
						<li><?php echo $html->link('Account Page', '/help/users/account'); ?></li>
					</ul><br />
					<h3>Discussions Pages</h3>
					<ul>
						<li><?php echo $html->link('Discussions Page', '/help/discussions/index'); ?></li>
						<li><?php echo $html->link('Group Discussions', '/help/discussions/group'); ?></li>
						<li><?php echo $html->link('Project Discussions', '/help/discussions/project'); ?></li>
						<li><?php echo $html->link('View Discussions', '/help/discussions/view'); ?></li>
						<li><?php echo $html->link('Add Discussion', '/help/discussions/add'); ?></li>
						<li><?php echo $html->link('Edit Discussion', '/help/discussions/edit'); ?></li>
						<li><?php echo $html->link('Discussion Topics', '/help/discussions/topics'); ?></li>
						<li><?php echo $html->link('Discussion Posts', '/help/discussions/posts'); ?></li>
					</ul><br />
					<h3>Documents Pages</h3>
					<ul>
						<li><?php echo $html->link('Documents Page', '/help/docs/index'); ?></li>
						<li><?php echo $html->link('User Documents', '/help/docs/user'); ?></li>
						<li><?php echo $html->link('Group Documents', '/help/docs/group'); ?></li>
						<li><?php echo $html->link('Project Documents', '/help/docs/project'); ?></li>
						<li><?php echo $html->link('Document View', '/help/docs/view'); ?></li>
						<li><?php echo $html->link('Document Versions', '/help/docs/versions'); ?></li>
						<li><?php echo $html->link('Add Document', '/help/docs/add'); ?></li>
						<li><?php echo $html->link('Edit Document', '/help/docs/edit'); ?></li>
					</ul><br />
				</div>
				<div class="col2">
					<h3>Group Pages</h3>
					<ul>
						<li><?php echo $html->link('Group Dashboard', '/help/groups/dashboard'); ?></li>
						<li><?php echo $html->link('Groups Page', '/help/groups/user'); ?></li>
						<li><?php echo $html->link('Group Members', '/help/groups/members'); ?></li>
						<li><?php echo $html->link('Add Group Members', '/help/groups/adduser'); ?></li>
						<li><?php echo $html->link('Create Group', '/help/groups/create'); ?></li>
						<li><?php echo $html->link('Edit Group', '/help/groups/edit'); ?></li>
					</ul><br />
					<h3>Inbox Pages</h3>
					<ul>
						<li><?php echo $html->link('Inbox', '/help/inbox/received'); ?></li>
						<li><?php echo $html->link('View Message', '/help/inbox/view'); ?></li>
						<li><?php echo $html->link('Send Message', '/help/inbox/send'); ?></li>
						<li><?php echo $html->link('Sent Messages', '/help/inbox/sent'); ?></li>
						<li><?php echo $html->link('Message Trash', '/help/inbox/trash'); ?></li>
						<li><?php echo $html->link('Message Archives', '/help/inbox/archives'); ?></li>
					</ul><br />
					<h3>Notes Pages</h3>
					<ul>
						<li><?php echo $html->link('Add Note', '/help/notes/add'); ?></li>
						<li><?php echo $html->link('Edit Note', '/help/notes/edit'); ?></li>
					</ul><br />
					<h3>Preferences and Settings</h3>
					<ul>
						<li><?php echo $html->link('User Preferences', '/help/preferences/index'); ?></li> 
						<li><?php echo $html->link('Group Settings', '/help/settings/group'); ?></li>
						<li><?php echo $html->link('Project Settings', '/help/settings/project'); ?></li>
					</ul><br />
				</div>
				<div class="col3">
					<h3>Project Pages</h3>
					<ul>
						<li><?php echo $html->link('Project Dashboard', '/help/projects/dashboard'); ?></li>
						<li><?php echo $html->link('Projects Page', '/help/projects/group'); ?></li>
						<li><?php echo $html->link('Project Members', '/help/projects/members'); ?></li>
						<li><?php echo $html->link('Add Project Members', '/help/projects/adduser'); ?></li>
						<li><?php echo $html->link('Create Project', '/help/projects/create'); ?></li>
						<li><?php echo $html->link('Edit Project', '/help/projects/edit'); ?></li>
					</ul><br />
					<h3>Search Page</h3>
					<ul>
						<li><?php echo $html->link('Search', '/help/search/index'); ?></li> 
					</ul><br />
					<h3>Document Types</h3>
					<ul>
						<li><?php echo $html->link('Group Document Types', '/help/types/group'); ?></li>
						<li><?php echo $html->link('Project Document Types', '/help/types/project'); ?></li>
						<li><?php echo $html->link('Add Document Type', '/help/types/add'); ?></li>
						<li><?php echo $html->link('Edit Document Type', '/help/types/edit'); ?></li>
					</ul><br />
					<h3>Links Pages</h3>
					<ul>
						<li><?php echo $html->link('Group Links', '/help/urls/group'); ?></li>
						<li><?php echo $html->link('Project Links', '/help/urls/project'); ?></li>
						<li><?php echo $html->link('Add Link', '/help/urls/add'); ?></li>
						<li><?php echo $html->link('Edit Link', '/help/urls/edit'); ?></li>
						<li><?php echo $html->link('Link View', '/help/urls/view'); ?></li>
					</ul><br />
				</div>
			</div>
		</div>
	</div>
</div>
