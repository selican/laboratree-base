<?php
	$static = Configure::read('Site.static_url');
?>
<?php echo $html->docType('xhtml-trans'); ?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en" dir="ltr">
	<head>
		<title>
			<?php
				$title = 'Laboratree';
				if(!empty($title_for_layout))
				{
					$title = $title_for_layout . ' - ' . $title;
				}
				echo $title;
			?>
		</title>
		<?php
			echo $html->charset('utf-8');
			echo $html->meta('icon', 'http://static.selican.com/img/favicon.ico', array('rel' => 'icon'));
		?>
		<!--link rel="search"
			type="application/opensearchdescription+xml"
			title="<?php echo Configure::read('Site.name'); ?>"
			href="<?php echo $html->url('/search/opensearch.xml'); ?>" /-->

		<?php
			echo $html->css('ext-all.css');
			echo $this->element('csshandler');
			echo $html->css('xtheme-site.css');
			echo $html->css('xtheme-leftNav.css');
		?>
		<!--[if IE 6]>
		<?php echo $html->css('ie6.css'); ?>
		<![endif]-->
		<?php
			if(isset($javascript))
			{
				echo $javascript->link('jquery/jquery-1.3.2.min.js');
		
				echo $javascript->link('extjs/adapter/ext/ext-base-debug.js');
				echo $javascript->link('extjs/ext-all-debug.js');
			
				echo $javascript->link('http://www.google.com/recaptcha/api/js/recaptcha_ajax.js');
				
				echo $javascript->link('laboratree.js');
				echo $javascript->link('site.js');
				echo $javascript->link('navigation.js');
				echo $javascript->link('validation.js');
				echo $javascript->link('links.js');
	
				if($session->check('Auth.User'))
				{
					echo $javascript->link('session.js');
				}
			
				echo $this->element('jshandler');
				echo $javascript->link('main.js');
			}
		?>
	</head>
	<body>
		<div class="wrapper">
			<div id="header">
				<div class="navSet">
					<?php echo $html->image('http://static.selican.com/img/navEndl.png', array('class'=>'navLT')); ?>
					<?php
						echo $this->element('navigation');

					?>
					<?php echo $html->image('http://static.selican.com/img/navEndr.png', array('class'=>'navLR')); ?>

				</div>
				
				<?php echo $html->image('http://static.selican.com/img/LTlogo.png', array('class'=>'logoLT')); ?>
				
				<div class="navBottom">
				<?php
					if(isset($grouplist))
					{
						echo $this->element('grouplist', array('grouplist' => $grouplist));
					}

					/*
					echo $this->element('search');
					*/
				?>
				</div>
			</div>
			<div class="mainBox"><b class="tc"><b class="L1"></b><b class="L2"></b><b class="L3"></b></b><div class="content">
				<div class="crumbsBox">
    					<b class="tc"><b class="L1"></b><b class="L2"></b><b class="L3"></b></b>
        				<div class="content">
						<div class="bcSpace">
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
										echo '<br />' . $breadcrumbs; 
								?>
							</div>
						</div>
					</div>
					<b class="bc"><b class="L3"></b><b class="L2"></b><b class="L1"></b></b>
				</div>
					
				<?php
					if($session->check('Auth.User'))
					{
						echo $this->element('navhandler', array('params' => $this->viewVars));
					}
		
					/*
					if(Configure::read('Chat.enabled'))
					{
						 echo $this->renderElement('chat'); 
					}
					*/
				?>
				<div class="mainBG">
					<div class="rightSide">
						<?php
							$session->flash('status');
							$session->flash('auth');
			
							echo $content_for_layout;

							/*
							if(Configure::read('Chat.enabled'))
							{
								echo $this->renderElement('chat');
							}
							*/
						?>
			   		</div>
				</div>
			</div>
			
				<b class="bc"><b class="L3"></b><b class="L2"></b><b class="L1"></b></b>
			</div>

			<div class="footinfo">
				<div class="fixedwidth">Copyright 2010 <a href="http://www.selican.com/">Selican Technologies, Inc.</a></div>
			</div>
		</div>
	</body>
</html>
