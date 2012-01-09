<?php
	$html->addCrumb('Help', '/help/site');
	$html->addCrumb('Get Started', '/help/site/getstarted');
	$html->addCrumb('Projects - Add Members', '/help/projects/adduser/');
?>

<div class="help">
	<div class="helpText">
		<h2>Add Project Members Page</h2>

		<br />

		<p>This page provides three ways in which a project manager may add other laboratree users to their project.</p>

		<br />

		<ol>
			<li>
				Add Colleagues
				<p>This tab contains a list of all the users with whom you share other projects and projects, and who are not in your current project.  To add one or more of them to your project simply check the boxes to the left of their names.</p>
			</li>
			<li>
				Search Users
				<p>The search tab allows you to enter a name or partial name and will return a list of all the users in your Laboratree instance who match.  You can select these users in the same way as the Add Colleagues tab.</p>
			</li>
			<li>
				Add by Email
				<p>The 'Add by Email' tab is primarily for adding members who do not yet have Laboratree accounts.  Simply enter one or more email addresses, separated by commas, into the text area.  If that email address is already in the database that user will be added to your project, if not, a new user account will be created and a temporary password will be sent to the email address provided.</p>
			</li>
		</ol>

		<br />

		<p>The "Add" button in the lower right corner of the tab panel will process each of the three tabs simultaneously.  There is no need to click it on a specfic tab.</p>
	</div>
</div>
