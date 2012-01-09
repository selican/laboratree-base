<?php
	$html->addCrumb('Help', '/help/site');
	$html->addCrumb('Chat', '/help/chat');
	$html->addCrumb('Pidgin', '/help/chat/pidgin');
?>
<div class="help">
	<h2>Configuring Pidgin for the Laboratree XMPP Server</h2>
	
	<p><?php echo $html->link('Pidgin', 'http://www.pidgin.im'); ?> is a multi-platform multi-protocol instant messaging client. Windows users who wish to to download Pidgin should go to <?php echo $html->link('http://pidgin.im/', 'http://www.pidgin.im'); ?>. The installer is relatively straightforward. Pidgin can be setup to use a variety of protocols, such as AIM, IRC, and Jabber.</p>
	
	<p>1.) Open Pidgin.</p>
	<p>2.) From the <b>Accounts</b> menu, click <b>Manage Accounts (Ctrl+A)</b>.</p>
	<p><?php echo $html->image('help/chat/pidgin_01.png', array('alt' => 'Pidgin Start')); ?></p>
	<p>3.) Click <b>Add</b> from the <b>Accounts</b> window.</p>
	<p><?php echo $html->image('help/chat/pidgin_02.png', array('alt' => 'Welcome to Pidgin')); ?></p>
	<p>4.) Enter the following information into the <b>Add Account</b> window.
	<p>
		<ul>
			<li><b>Protocol</b>: XMPP (or Jabber depending on Pidgin version)</li>
			<li><b>Screen Name</b>: This is your <b>Laboratree Username</b> <i>(Ex: <?php echo (($session->check('Auth.User')) ? $session->read('Auth.User.username') : ''); ?>)</i></li>
			<li><b>Domain</b>: <?php echo Configure::read('Chat.domain'); ?></li>
			<li><b>Resource</b>: This field is a label for your convenience</li>
			<li><b>Password</b>: This is your <b>Laboratree Password</b></li>
			<li><b>Local Alias</b>: Leave this field blank</li>
			<li><b>Remember Password</b>: Check the box if you'd like Pidgin to remember your password</li>
		</ul>
	</p>
	<p><?php echo $html->image('help/chat/pidgin_03.png', array('alt' => 'Add Account')); ?></p>
	<p>5.) Click <b>Save</b>.</p>
	<p>You are now ready to connect to the Laboratree Chat service through Pidgin.</p>
	<h3>Adding Colleagues</h3>
	<p>1.) From the <b>Buddies</b> menu, cick <b>Add Buddy (Ctrl+B)</b>.</p>
	<p>2.) Enter the screen name of the person you wish to add, <b>append with @<?php echo Configure::read('Chat.domain'); ?>.</b><i>(Ex: labuser@<?php echo Configure::read('Chat.domain'); ?>)</i></p>
	<p><?php echo $html->image('help/chat/pidgin_04.png', array('alt' => 'Add Buddy')); ?></p>
	<p>3.) Click <b>Add</b>.</p>
	<h3>Adding Multi-User Chat Rooms</h3>
	<p>1.) From the <b>Buddies</b> menu, click <b>Add Chat</b>.</p>
	<p>2.) Enter the following information into the <b>Add Chat</b> window.</p>
	<p>
		<ul>
			<li><b>Room</b>: This is the <b><?php echo $html->link('Room Identifier', '/help/chat/room_id'); ?></b> of the chat room you would like to add.</li>
			<li><b>Server</b>: chat.<?php echo Configure::read('Chat.domain'); ?></i></li>
			<li><b>Handle</b>: This is your identifier in the room. <i>(Ex: <?php echo (($session->check('Auth.User')) ? $session->read('Auth.User.name') : ''); ?>)</i></li>
			<li><b>Password</b>: This is the <b><?php echo $html->link('Room Password', '/help/chat/room_pass'); ?></b> of the chat room you would like to add.</li>
			<li><b>Alias</b>: The name you would like to assign in your chat list for the room.</li>
		</ul>
	</p>
	<p><?php echo $html->image('help/chat/pidgin_05.png', array('alt' => 'Add Chat')); ?></p>
</div>
