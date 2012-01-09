<div id="grouplist-div">
	<?php
		if(isset($grouplist))
		{
			echo $form->select('Navigation.group', $grouplist, null, array('onchange' => 'laboratree.navigation.grouplist(this);'), 'Dashboards');
		}
	?>
</div>
