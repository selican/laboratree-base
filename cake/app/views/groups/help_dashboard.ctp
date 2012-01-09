<?php
	$html->addCrumb('Help', '/help/site');
	$html->addCrumb('Get Started', '/help/site/getstarted');
	$html->addCrumb('Group Dashboard', '/help/groups/dashboard');
?>

<div class="help">
	<div class="helpText">
		<h2>Dashboard</h2>

		<br />

		<p>Like the user dasboard, the group dashboard is the main screen for managing a group in Laboratree. It allows you to see many of the same panles from your user dashboard, but only for one particular group. The panels can be collapsed, or rearranged to suit your needs here as well, and the positions will be remembered in your session cookie.</p>

		<p>For an explaination of the layout, and some instructions for common tasks see below.</p>

		<br />

		<h3>Dashboard Features</h3>

		<?php echo $html->image('http://selican.com/UPLOAD/dashboard.gif', array('alt' => 'Dashboard', 'class' => 'floatRightClear')); ?>

		<ol>
			<li>
				Drop Down Navigation
				<p>Allows you navigate directly to the dashboard for any group or project of which you are a member.</p>
			</li>
			<li>
				User Navigation
				<p>This navigation bar has links to view or edit your user profile, view your laboratree inbox, and logout of the sytem.</p>
			</li>
			<li>
				Search
				<p>Allows you to search for any site content by name.  Results are displayed from all categories with matches (documents, notes, users, groups, etc.)</p></li>
			<li>
				Panel Buttons
				<p>Several panels have buttons in their upper right corner, you can see their function by hovering your mouse cursor over them.</p>
			</li>
			<li>
				Panel Links
				<p>Many of the fields in panels are links to other areas of the site.  For example, clicking the name of a group in your groups panel will take you to that group's dashboard.</p>
				<br />
				<p><b>If you are a group manager, or have been granted permission, some panels will have aditional functionality. For example clicking 'add member' at the top of the members panel will allow you to add other Laboratree users to the group.</b></p>
			</li>
			<li>
				Main Navigation
				<p>The main navigation bar appears at the top of any Laboratree page that you visit.  The tabs  will take you to the major areas of the site, and are context sensitive based on your current page.</p>
			</li>
			<li>
				Action Bar
				<p>Below the main navigation bar is a set of more common actions on your current page.</p>
			</li>
			<li>
				Breadcrumbs
				<p>The list of breadcrumbs shows you your current location in Laboratree.  Breadcrumbs are click-able and will take you back to a previous page.</p>
			</li>
		</ol>
		<p>Most of the columns in the panels can be sorted by clicking the column header.  Hovering your mouse cursor over the right edge of a column will allow you to sort, or toggle a column on or off.</p>
		<p>Some of the panels, such as the documents panel, have functionality available through right-clicking. Right clikcing on a folder in the document tree, for example allows you to upload a document directly to that folder.</p>
	</div>

</div>
