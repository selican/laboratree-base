<?php
/**
 * AppError class
 *
 * Extends ErrorHandler with Custom Errors
 *
 * PHP version 5
 *
 * LICENSE: 
 *
 * @category  Model
 * @package   Laboratree
 * @author    Brandon Peters <brandon.peters@selican.com>
 * @copyright 2010 Selican Technologies, Inc.
 * @license   http://www.selican.com/licenses/selican_1_01.txt Selican Technologies License 1.01
 * @version   SVN: $Id: app_error.php 2070 2011-06-20 20:28:00Z pserguta $
 * @link      http://hoth.selican.com/package/Laboratree
 */

/**
 * AppError class
 *
 * @category  Model
 * @package   Laboratree
 * @author    Brandon Peters <brandon.peters@selican.com>
 * @copyright 2010 Selican Technologies, Inc.
 * @license   http://www.selican.com/licenses/selican_1_01.txt Selican Technologies License 1.01
 * @version   Release: @package_version@
 * @link      http://hoth.selican.com/package/Laboratree
 */
class AppError extends ErrorHandler
{
	/**
	 * Missing Field
	 *
	 * @param array $params Parameters
	 *
	 * @return null
	 */
	function missing_field($params)
	{
		$this->controller->set('field', $params['field']);
		$this->_outputMessage('missing_field');
	}

	/**
	 * Invalid Field
	 *
	 * @param array $params Parameters
	 *
	 * @return null
	 */
	function invalid_field($params)
	{
		$this->controller->set('field', $params['field']);
		if(isset($params['additional']))
		{
			$this->controller->set('additional', $params['additional']);
		}
		$this->_outputMessage('invalid_field');
	}

	/**
	 * Access Denied
	 *
	 * @param array $params Parameters
	 *
	 * @return null
	 */
	function access_denied($params)
	{
		$action   = 'view';
		$resource = 'resource';

		if(isset($params['action']))
		{
			$action = $params['action'];
		}

		if(isset($params['resource']))
		{
			$resource = $params['resource'];
		}

		$this->controller->set('action', $action);
		$this->controller->set('resource', $resource);

		$this->_outputMessage('access_denied');
	}

	/**
	 * Unconfirmed
	 *
	 * @param array $params Parameters
	 *
	 * @return null
	 */
	function unconfirmed($params)
	{
		$this->_outputMessage('unconfirmed');
	}

	/**
	 * Internal Error
	 *
	 * @param array $params Parameters
	 *
	 * @return null
	 */
	function internal_error($params)
	{
		$action   = 'view';
		$resource = 'resource';

		if(isset($params['action']))
		{
			$action = $params['action'];
		}

		if(isset($params['resource']))
		{
			$resource = $params['resource'];
		}

		$this->controller->set('action', $action);
		$this->controller->set('resource', $resource);

		$this->_outputMessage('internal_error');
	}

	/**
	 * Unavailable
	 *
	 * @param array $params Parameters
	 *
	 * @return null
	 */
	function unavailable($params)
	{
		$resource = 'resource';
		$reason   = '';

		if(isset($params['resource']))
		{
			$resource = $params['resource'];
		}

		if(isset($params['reason']))
		{
			$reason = $params['reason'];
		}

		$this->controller->set('resource', $resource);
		$this->controller->set('reason', $reason);

		$this->_outputMessage('unavailable');
	}

	/**
	 * Outputs Error Message
	 *
	 * @param string $template Error Template
	 *
	 * @return null
	 */
	function _outputMessage($template)
	{
		if(isset($this->controller->RequestHandler))
		{
			if($this->controller->RequestHandler->prefers('json'))
			{
				$this->controller->layout = 'json/default';

				$template = 'json/' . $template;
			}

			if($this->controller->RequestHandler->prefers('extjs'))
			{
				$this->controller->layout = 'extjs/default';

				$template = 'json/' . $template;
			}

			if($this->controller->RequestHandler->prefers('js'))
			{
				$this->controller->layout = 'js/default';

				$template = 'js/' . $template;
			}

			if($this->controller->RequestHandler->prefers('xml'))
			{
				$this->controller->layout = 'xml/default';

				$template = 'xml/' . $template;
			}
		}

		$this->controller->render($template);
		$this->controller->afterFilter();
		echo $this->controller->output;
	}
}
?>
