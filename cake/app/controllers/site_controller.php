<?php
class SiteController extends AppController
{
	var $name = 'Site';

	var $uses = array();

	var $components = array(
		'Auth',
		'Security',
		'Session',
	);

	function beforeFilter()
	{
		parent::beforeFilter();
	}

	/**
	 * Main Index for Help
	 * Allows JSON calls to retrieve help for Help Popups
	 *
	 * @param string $type   Type
	 * @param string $action Action
	 */
	function help_index($type = '', $action = '')
	{
		$this->pageTitle = 'Help';
		$this->set('pageName', 'Help');

		if($this->RequestHandler->prefers('json'))
		{
			if(empty($type))
			{
				$this->cakeError('missing_field', array('field' => 'Type'));
				return;
			}

			if(!is_scalar($type))
			{
				$this->cakeError('invalid_field', array('field' => 'Type'));
				return;
			}

			if(empty($section))
			{
				$this->cakeError('missing_field', array('field' => 'Section'));
				return;
			}

			if(!is_scalar($section))
			{
				$this->cakeError('invalid_field', array('field' => 'Section'));
				return;
			}

			$help = $this->Help->find('first', array(
				'conditions' => array('
					Help.type' => $type,
					'Help.section' => $section,
				),
				'recursive' => 1,
			));
			if(empty($help))
			{
				$this->cakeError('invalid_field', array('field' => 'Section'));
				return;
			}

			$node = $this->Help->toNode($help);

			$response = array(
				'success' => true,
				'help' => $node,
			);
			$this->set('response', $response);
		}
	}

	/**
	 * Getting Started Guide
	 */
	function help_getstarted()
	{
		$this->pageTitle = 'Help - Table of Contents';
		$this->set('pageName', 'Help - Table of Contents');
	}
}
?>
