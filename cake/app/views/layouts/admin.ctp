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
		<link rel="search"
			type="application/opensearchdescription+xml"
			title="<?php echo Configure::read('Site.name'); ?>"
			href="<?php echo $html->url('/search/opensearch.xml'); ?>" />

		<?php
			echo $html->css('ext-all.css', null, null, false);
			echo $this->renderElement('csshandler');
			echo $html->css('xtheme-site.css', null, null, false);
			echo $html->css('xtheme-leftNav.css', null, null, false);
		?>
		<!--[if IE 6]>
		<?php echo $html->css('ie6.css'); ?>
		<![endif]-->
		<?php
			if(isset($javascript))
			{
				echo $javascript->link('jquery/jquery-1.3.2.min.js', false);
		
				if(Configure::read('debug') > 0)
				{
					echo $javascript->link('extjs/adapter/ext/ext-base-debug.js');
					echo $javascript->link('extjs/ext-all-debug.js');
				}
				else
				{
					echo $javascript->link('extjs/adapter/ext/ext-base.js', false);
					echo $javascript->link('extjs/ext-all.js', false);
				}
			
				echo $javascript->link('http://www.google.com/recaptcha/api/js/recaptcha_ajax.js', false);
				
				echo $javascript->link('laboratree.js', false);
				echo $javascript->link('navigation.js', false);
				echo $javascript->link('validation.js', false);
				echo $javascript->link('links.js', false);
	
				if($session->check('Auth.User'))
				{
					echo $javascript->link('session.js', false);
					echo $javascript->link('admin.js', false);
				}
			
				echo $this->renderElement('jshandler');
				echo $javascript->link('main.js', false);
			}

			//echo $scripts_for_layout;
			echo $asset->scripts_for_layout();
		?>
	</head>
	<body>
		<div class="wrapper">
			<div id="header">
				<div class="navSet">
					<?php echo $html->image('http://static.selican.com/img/navEndl.png', array('class'=>'navLT')); ?>
					<?php
						echo $this->renderElement('navigation');

					?>
					<?php echo $html->image('http://static.selican.com/img/navEndr.png', array('class'=>'navLR')); ?>

				</div>
				<?php echo $html->image('http://static.selican.com/img/LTlogo.png', array('class'=>'logoLT')); ?>
				<div class="navBottom">
				<?php
					if(isset($grouplist))
					{
						echo $this->renderElement('grouplist', array('grouplist' => $grouplist));
					}

					echo $this->renderElement('search');
				?>
				</div>
			</div>
			<div class="roundcont">
				<div class="roundTop">
					<?php echo $html->image('http://static.selican.com/img/topMainBG.png', array('width' => 980, 'height' => 15)); ?>
					<div class="crumbsBG">
						<div class="breadCrumbs">
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
					</div>
					<div class="bcBottom"><?php echo $html->image('http://static.selican.com/img/crumbsBot.png', array('width' => 980, 'height' => 5)); ?></div>
				</div>
				<?php
					if(!$session->check('Auth.User'))
					{
						//
					}
					else
					{
						echo $this->renderElement('navhandler', array('params' => $this->viewVars));
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
							$workflowHlp->render();

							$session->flash('status');
							$session->flash('auth');
			
							echo $content_for_layout;
						?>
			   		</div>
				</div>
				<div class="roundBottom">
					<?php echo $html->image('http://static.selican.com/img/bottomMainBG.png', array('width' => 982, 'height' => 16)); ?>
				</div>
				
			</div>
			<div class="footinfo">
				<div class="fixedwidth">Copyright 2010 <a href="http://www.selican.com/">Selican Technologies, Inc.</a></div>
			</div>
		</div>
		<?php //echo $asset->scripts_for_layout(array('js', 'codeblock')); ?>
	</body>
</html>
