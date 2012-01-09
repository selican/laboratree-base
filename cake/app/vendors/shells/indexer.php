<?php
class IndexerShell extends Shell {
	var $uses = array(
		'Discussion',
		'Doc',
		'Group',
		'Inbox',
		'Interest',
		'Project',
		'User',
		'Journal',	
	);

	var $components = array(
		'Lucene',
	);

	var $store = array(
		'Discussion' => array(
			'title',
			'content',
		),
		'Doc' => array(
			'title',
			'name',
			'description',
		),
		'Group' => array(
			'name',
			'description',
		),
		'Message' => array(
			'subject',
			'body',
		),
		'Interest' => array(
			'name',
			'keyword',
		),
		'Project' => array(
			'name',
			'description',
		),
		'User' => array(
			'name',
			'description',
		),
		'Journal' => array(
			'title',
			'content',
		),
	);

	function _loadComponents(&$object)
	{
		foreach($object->components as $component)
		{
			App::import('Component', $component);	

			$componentCn = $component . 'Component';
			$object->{$component} =& new $componentCn(null);
			$object->{$component}->enabled = true;

			if(isset($object->{$component}->components))
			{
				$this->_loadComponents($object->{$component});
			}
		}
	}

	function initialize()
	{
		$this->_loadComponents($this);

		App::import('Core', 'Controller');
		$this->Controller =& new Controller();
		$this->Lucene->initialize($this->Controller);

		parent::initialize();
	}

	function main()
	{
		$indexPath = $this->Lucene->getIndexPath();
		$index = $this->Lucene->create();

		echo "Creating Index: $indexPath\n";

		foreach($this->uses as $model)
		{
			$data = $this->$model->find('all', array('recursive' => 1));
			if(empty($data))
			{
				continue;
			}

			foreach($data as $item)
			{
				$doc = $this->$model->toLuceneDoc($model, $model, $item);
				if(empty($doc))
				{
					continue;
				}

				$index->addDocument($doc);
			}
		}

		echo "# Committing Index\n";

		$index->commit();
		$index->optimize();
	}
}
?>
