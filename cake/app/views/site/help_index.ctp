<?php
	$html->addCrumb('Help', '/help/site');
?>
<div class="help">
	<ul>
		<li><?php echo $html->link('Getting Started', '/help/site/getstarted'); ?></li>
		<li><?php echo $html->link('Set Up Chat', '/help/chat'); ?></li>

		<?php foreach($plugins as $plugin): ?>
			<li><?php echo $html->link($plugin, '/help/' . Inflector::underscore($plugin)); ?></li>
		<?php endforeach; ?>
	</ul>
</div>
