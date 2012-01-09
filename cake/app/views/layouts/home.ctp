<?php
	$static = Configure::read('Site.static_url');
?>
<?php echo $html->docType('xhtml-trans'); ?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en" dir="ltr">
	<head>
		<title>
			<?php
				$title = 'NewNameHere';
				if(!empty($title_for_layout))
				{
					$title = $title_for_layout . ' - ' . $title;
				}
				echo $title;
			?>
		</title>

		<?php
			echo $html->charset('utf-8');
			echo $html->meta('icon', null, array('rel' => 'icon'));
		?>

		<link rel="search"
			type="application/opensearchdescription+xml"
			title="<?php echo Configure::read('Site.name'); ?>"
			href="<?php echo $html->url('/search/opensearch.xml'); ?>" />

		<?php echo $html->css('ext-all.css', null, null, false); ?>
		<?php echo $this->renderElement('csshandler'); ?>
		<?php echo $html->css('xtheme-site.css'); ?>
		<?php echo $html->css('xtheme-leftNav.css'); ?> 

		<!--[if IE 6]>
		<?php echo $html->css('ie6.css'); ?>
		<![endif]-->
		<?php
			if(Configure::read('debug') > 0)
			{
				echo $html->css('chat.css');
			}
		?>

		<!--[if IE 6]>
		<?php echo $html->css('ie6.css'); ?>
		<![endif]-->

		<?php
			if(isset($javascript))
			{
				echo $javascript->link('jquery/jquery-1.3.2.min.js');
		
				if(Configure::read('debug') > 0)
				{
					echo $javascript->link('extjs/adapter/ext/ext-base-debug.js');
					echo $javascript->link('extjs/ext-all-debug.js');
				}
				else
				{
					echo $javascript->link('extjs/adapter/ext/ext-base.js');
					echo $javascript->link('extjs/ext-all.js');
				}
			
				echo $javascript->link('http://www.google.com/recaptcha/api/js/recaptcha_ajax.js');
	
				echo $javascript->link('laboratree.js');
	
				if($session->check('Auth.User'))
				{
					echo $javascript->link('session.js');
	
					if(Configure::read('Chat.enabled'))
					{
						echo $javascript->link('strophe/strophe.js');
						echo $javascript->link('chat.js');
					}
				}
			
				echo $this->renderElement('jshandler');
				echo $javascript->link('main.js');
			}
		?>

		<?php echo $this->renderElement('jshandler'); ?>
		<?php echo $javascript->link('main.js', false); ?>

		<?php echo $asset->scripts_for_layout(); ?>
	</head>
<body>
<div class="wrapper">
	<div id="headerHOME">
		<div class="navSet">

				<?php
					echo $this->renderElement('navigation');
				?>		

		</div>
	</div>
	
	<div class="roundcont">
			<div class="roundTop">
				<img src="/img/topMainBG.jpg" width="980" height="15" />
				<div class="crumbsBG">
					<div class="breadCrumbs">
							<div class="bcSpaceHOME">
								<?php if(!empty($pageName)): ?>
									<div class="bcHome"><?php echo $pageName;?></div>
								<?php endif; ?>
								<div class="bcLocation">
									<?php
										$breadcrumbs = $html->getCrumbs(' > ', 'Home');
										if(empty($breadcrumbs))
										{
											$breadcrumbs = $html->link('Home', '/');
										}
			
										echo $breadcrumbs; 
									?>
								</div>
						
					</div>
				</div>
			</div>
		
			<div class="bcBottom"><img src="/img/crumbsBot.png" width="980" height="5" /></div>		
		</div>
	
			<div class="mainBG">
				<div class="leftSide">
				
				<div id="left_inner">
						<?php echo $this->renderElement('leftstatic'); ?>
						<?php echo $this->renderElement('search'); ?>

						<?php
							if(!$session->check('Auth.User'))
							{
								echo $this->renderElement('registerad');
							}
							else
							{
								echo $this->renderElement('navhandler', array('params' => $this->viewVars));
							}
						?>
						
						<?php echo $this->renderElement('chat'); ?>
					</div>
		
			</div>
		
	<div class="rightSide">
	
		<?php echo $content_for_layout; ?>
		
	   </div>
	</div>
		<div class="roundBottom">
			<img src="/img/bottomMainBG.png" width="982" height="16" />
		</div>


	<div class="footinfo">
		<div class="fixedwidth">Copyright 2010 <a href="http://www.selican.com/">Selican Technologies, Inc.</a></div>
	</div>
	
</div>


</body>
</html>
