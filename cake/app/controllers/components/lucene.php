<?php 
App::import('vendor', 'ZendLucene', array('file' => 'Zend' . DS . 'Search' . DS . 'Lucene.php'));

class LuceneComponent extends Object
{
	private $index = null;
	private $indexPath = null;

	function initialize(&$controller)
	{
		$this->Controller =& $controller;

		if(empty($indexPath))
		{
			$this->indexPath = TMP . DS . 'index';
		}
	}

	function startup(&$controller)
	{
		if(empty($indexPath))
		{
			$this->indexPath = TMP . DS . 'index';
		}
	}

	/**
	 * Create Search Index
	 *
	 * @return object Search Index
	 */
	function create()
	{
		return Zend_Search_Lucene::create($this->indexPath);
	}

	/**
	 * Retrieve Index Path
	 *
	 * @return string Index Path
	 */
	function getIndexPath()
	{
		return $this->indexPath;
	}

	/**
	 * Set Index Path
	 *
	 * @param string $path Index Path
	 *
	 * @throws InvalidArgumentException
	 */
	function setIndexPath($path)
	{
		if(empty($path) || !is_string($path))
		{
			throw new InvalidArgumentException('Invalid path.');
		}

		if(file_exists(dirname($path)))
		{
			$this->indexPath = $path;
		}
	}

	/**
	 * Get Search Index
	 *
	 * @return object Search Index
	 */
	function &getIndex()
	{
		if(!$this->index)
		{
			$this->index = Zend_Search_Lucene::open($this->indexPath);
		}
		return $this->index;
	}

	/**
	 * Query Search Index
	 *
	 * @param string $query Search Query
	 *
	 * @throws InvalidArgumentException
	 * @throws RuntimeException
	 *
	 * @return array Search Results
	 */
	function query($query)
	{
		if(empty($query) || !is_string($query))
		{
			throw new InvalidArgumentException('Invalid query.');
		}

		$index =& $this->getIndex();
		$parsedQuery = '';

		$query = str_replace(array('?', '*'), '', $query);

		try
		{
			$parsedQuery = Zend_Search_Lucene_Search_QueryParser::parse($query);
		}
		catch(Exception $e)
		{
			throw RuntimeException($e->getMessage());
		}

		$results = $index->find($parsedQuery);
		return $results;
	}
}
?>
