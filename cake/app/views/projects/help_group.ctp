<?php
	$html->addCrumb('Help', '/help/site');
	$html->addCrumb('Get Started', '/help/site/projects');
	$html->addCrumb('Dashboard', '/help/projects/group');
?>

<div class="help">
	<div class="helpText">
		<h2>Projects</h2>

		<br />

		<p>1. Create a Project</p>
		<p>2. Add Members</p>

		<div class="helpText">
			<h3>Create a Project</h3>

			<ul>
				<li>To create a project, select the 'create group' link or the '+' on Projects dashboard.</li>
				<li>Type Project Name in the text box.</li>
				<li>The description is optional, but can be useful for adding details about the project.</li>
					<div class="hints">Group and Project names must be at least two characters long.</div>
			</ul>
		</div>

		<div class="helpImg">
			<ul>
				<li>
					<?php echo $html->image('http://selican.com/UPLOAD/GS_CreateGroup.gif', array('alt' => 'Create Project')); ?><div class="clear"></div>
				</li>
			</ul>
		</div>

		<div class="helpText">
			<h3>Add Members</h3>

			<ul>
				<li>The next step is inviting/adding members. You can either add your colleagues from other groups, search for other users, subscribing to Laboratree or add member by email.</li>
				<li>Make selections under each tab and click the add button.</li>
				<li>After selecting add a pop up will appear showing which users or emails were added, which then will then appear in the Pending Invitations box.</li>
				<li>Once an user accepts an invitation their name or email will be removed from the Pending Invitations box.</li>
					<div class="hints">Add by email, creates an account and temporary password for user added.</div>
			</ul>
		</div>

		<div class="helpImg">
			<ul>
				<li>
					<?php echo $html->image('http://selican.com/UPLOAD/GS_InviteMembers.jpg', array('alt' => 'Add Members')); ?><div class="clear"></div>
				</li>
			</ul>
		</div>
	</div>
</div>
