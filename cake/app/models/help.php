<?php
class Help extends AppModel
{
	var $name = 'Help';

	/**
	 * Converts a record to a ExtJS Store node
	 *
	 * @param array $help   Help
	 * @param array $params Parameters
	 *
	 * @return array ExtJS Store Node
	 */
	 function toNode($help, $params = array())
	 {
		if(empty($help))
		{
			throw new InvalidArgumentException('Invalid Help');
		}

		if(!is_array($help))
		{
			throw new InvalidArgumentException('Invalid Help');
		}

		if(!empty($params))
		{
			if(!is_array($params))
			{
				throw new InvalidArgumentException('Invalid Parameters');
			}
		}

		if(!isset($params['model']))
		{
			$params['model'] = $this->name;
		}

		if(!is_string($params['model']))
		{
			throw new RuntimeException('Invalid Model');
		}

		$model = $params['model'];

		if(!isset($help[$model]))
		{
			throw new InvalidArgumentException('Invalid Model Key');
		}

		$required = array(
			'id',
			'type',
			'section',
			'content',
		);

		foreach($required as $key)
		{
			if(!array_key_exists($key, $group[$model]))
			{
				throw new InvalidArgumentException('Missing ' . strtoupper($key) . ' Key');
			}
		}

		$node = array(
			'id' => $help[$model]['id'],
			'type' => $help[$model]['type'],
			'section' => $help[$model]['section'],
			'content' => $help[$model]['content'],
		);

		return $node;
	}
}
?>
