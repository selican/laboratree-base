<?php
class PagesController extends AppController {
	var $name = 'Pages';

	var $uses = array();

	var $components = array(
		'Auth',
		'Security',
		'Session',
	);

	function beforeFilter()
	{
		$this->Auth->allow('display');

		parent::beforeFilter();
	}

	/**
	 * Displays an Admin Page
	 */
	function admin_display()
	{
		$path = func_get_args();

		$count = count($path);
		if(!$count)
		{
			$this->redirect('/');
		}

		$page = $subpage = $title = null;

		if(empty($path[0]))
		{
			$this->redirect('/');
			return;
		}

		$page = $path[0];

		if(!empty($path[1]))
		{
			$subpage = $path[1];
		}

		if(!empty($path[$count - 1]))
		{
			$title = Inflector::humanize($path[$count - 1]);
		}

		$this->set(compact('page', 'subpage', 'title'));
		$this->render('admin/' . join('/', $path));
	}

	/**
	 * Displays a Page
	 */
	function display()
	{
		$path = func_get_args();

		$count = count($path);
		if(!$count)
		{
			$this->redirect('/');
			return;
		}

		$page = $subpage = $title = null;

		if(empty($path[0]))
		{
			$this->redirect('/');
			return;
		}

		$page = $path[0];

		if(!empty($path[1]))
		{
			$subpage = $path[1];
		}

		if(!empty($path[$count - 1]))
		{
			$title = Inflector::humanize($path[$count - 1]);
		}

		$this->set(compact('page', 'subpage', 'title'));
		$this->render('user/' . join('/', $path));
	}
}
?>
