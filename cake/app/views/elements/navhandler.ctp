<?php
	$c = (!isset($c)) ? 'pages' : $c;
	$a = (!isset($a)) ? 'display' : $a;
	$context = (!isset($context)) ? array() : $context;

	$data = $this->requestAction(array(
		'controller' => 'navigation',
		'action' => 'tree',
	), array(
		'pass' => array(
			$c,
			$a,
			$context,
		),
		// 'cache' => '+1 hour',
	));

	if(!empty($data) && isset($navigation))
	{
		$navigation->substitute($data, $this->viewVars);
	}
?>
<div id="tabSet">
	<div id="tab-bar-top">
		<?php if(isset($data['tabs']) && !empty($data['tabs'])): ?>
		<ul>
			<?php
				foreach($data['tabs'] as $tab):
					if($tab['Navigation']['url'] == $this->here)
					{
						$tab['Navigation']['type'] = 'current';
					}
			?>
			<li class="<?php echo $tab['Navigation']['type']; ?>"><a href="<?php echo $html->url($tab['Navigation']['url']); ?>"><span><?php echo $tab['Navigation']['title']; ?></span></a></li>
			<?php endforeach; ?>
			<li><a id="chat" style="display: none;" href="#" onclick="laboratree.chat.showMenu(); return false;"><span>Chat</span></a></li>
		</ul>
		<?php endif; ?>
		<?php if(isset($data['subtabs']) && !empty($data['subtabs'])): ?>
		<div id="tab-bar-bottom">
			<ul id="tab-bar-bottom-entries">
				<?php foreach($data['subtabs'] as $tab): ?>
				<li><a href="<?php echo $html->url($tab['Navigation']['url']); ?>"><?php echo $tab['Navigation']['title']; ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php endif; ?>
	</div>
</div>
