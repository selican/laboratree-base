<?php
/* SVN FILE: $Id: routes.php 2522 2011-12-27 21:47:17Z brandon $ */
/**
 * Short description for file.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.2.9
 * @version       $Revision: 2522 $
 * @modifiedby    $LastChangedBy: brandon $
 * @lastmodified  $Date$
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/views/pages/home.ctp)...
 */
	Router::parseExtensions();

	Router::connect('/', array('controller' => 'users', 'action' => 'home'));
	Router::connect('/l/*', array('controller' => 'inbox', 'action' => 'link'));
	Router::connect('/r/*', array('controller' => 'users', 'action' => 'resetlink'));
	Router::connect('/v/*', array('controller' => 'users', 'action' => 'verify'));
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

	Router::connect('/chat/:action/*', array('controller' => 'chat'), array('named' => false));

	Router::connect('/admin', array('admin' => true, 'controller' => 'pages', 'action' => 'display', 'index'));
	Router::connect('/admin/pages/*', array('controller' => 'pages', 'action' => 'display', 'admin' => true));
	Router::connect('/admin/users/login', array('controller' => 'users', 'action' => 'login', base64_encode('/admin')));
	Router::connect('/admin/users/logout', array('controller' => 'users', 'action' => 'logout'));

	Router::connect('/help/:controller/:action/*', array('prefix' => 'help', 'help' => true));
?>
