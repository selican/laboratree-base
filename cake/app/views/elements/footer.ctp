<div id="foot">
	<table cellspacing="0" cellpadding="0" style="width: 100%;">
		<tr>
			<?php if($session->check('Auth.User')): ?>
			<td>
				<div id="chat_bar" style="display: table-cell;"></div>
			</td>
			<?php endif; ?>
			<td>
				<ul>
					<li><?php echo $html->link('About', '/pages/about'); ?></li>
					<li><?php echo $html->link('Privacy', '/pages/privacy'); ?></li>
					<li><?php echo $html->link('Terms of Use', '/pages/disclaimer'); ?></li>
					<li><?php echo $html->link('Help', '/pages/help'); ?></li>
				</ul>
			</td>
		</tr>
	</table>
</div>
