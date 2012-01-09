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
			echo $html->meta('icon', null, array('rel' => 'icon'));
		?>
		<?php if (Configure::read() == 0) { ?>
		<meta http-equiv="Refresh" content="<?php echo $pause; ?>;url=<?php echo $url; ?>"/>
		<?php } ?>
		<link rel="search"
			type="application/opensearchdescription+xml"
			title="<?php echo Configure::read('Site.name'); ?>"
			href="<?php echo $html->url('/search/opensearch.xml'); ?>" />

		<?php
			echo $html->css('ext-all.css');
			echo $this->renderElement('csshandler');
			echo $html->css('xtheme-site.css');
			echo $html->css('xtheme-leftNav.css');

			if(Configure::read('Chat.enabled'))
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
				
				echo $javascript->link('FormUtil.js');
				echo $javascript->link('laboratree.js');
				echo $javascript->link('navigation.js');
	
				if($session->check('Auth.User'))
				{
					echo $javascript->link('session.js');
	
					if(Configure::read('Chat.enabled'))
					{
						echo $javascript->link('cryptojs/crypto/crypto-min.js');
						echo $javascript->link('cryptojs/crypto-sha1/crypto-sha1.js');

						echo $javascript->link('strophe/strophe.js');
						echo $javascript->link('strophe/plugins/strophe.caps.js');
						echo $javascript->link('strophe/plugins/strophe.muc.js');
						echo $javascript->link('strophe/plugins/strophe.archive.js');
						echo $javascript->link('strophe/plugins/strophe.pep.js');
						echo $javascript->link('strophe/plugins/strophe.chatstate.js');
						echo $javascript->link('chat.js');
					}
				}
			
				echo $this->renderElement('jshandler');
				echo $javascript->link('main.js');
			}
		?>
	</head>
	<body>
		<div class="wrapper">
			<div id="header">
				<div class="navSet">
					<?php
						echo $this->renderElement('navigation');
					?>		
				</div>
			</div>
			<div class="roundcont">
				<div class="roundTop">
					<?php echo $html->image('topMainBG.png', array('width' => 980, 'height' => 15)); ?>
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
					<div class="bcBottom"><?php echo $html->image('crumbsBot.png', array('width' => 980, 'height' => 5)); ?></div>
				</div>
				<div class="mainBG">
					<div class="leftSide">
						<div id="left_inner">
							<?php
								echo $this->renderElement('leftstatic');
								echo $this->renderElement('search');
		
								if(!$session->check('Auth.User'))
								{
									echo $this->renderElement('registerad');
								}
								else
								{
									if(isset($grouplist))
									{
										echo $this->renderElement('grouplist', array('grouplist' => $grouplist));
									}

									echo $this->renderElement('navhandler', array('params' => $this->viewVars));
								}
		
								if(Configure::read('Chat.enabled'))
								{
									 echo $this->renderElement('chat'); 
								}
							?>
						</div>
					</div>
					<div class="rightSide">
						<p><a href="<?php echo $url; ?>"><?php echo $message; ?></a></p>
			   		</div>
				</div>
				<div class="roundBottom">
					<?php echo $html->image('bottomMainBG.png', array('width' => 982, 'height' => 16)); ?>
				</div>
				<div class="footinfo">
					<div class="fixedwidth">Copyright 2010 <a href="http://www.selican.com/">Selican Technologies, Inc.</a></div>
				</div>
			</div>
		</div>
	</body>
</html>
