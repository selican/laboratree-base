<?php
class AppModel extends Model
{
	var $actsAs = array('Containable');

	var $cacheQueries = true;

	function toList($root, $data, $params = array())
	{
		if(!is_string($root) || empty($root))
		{
			throw new InvalidArgumentException('Invalid root.');
		}

		if(!is_array($data))
		{
			throw new InvalidArgumentException('Invalid data.');
		}

		$list = array(
			'success' => true,
		);

		try {
			$list[$root] = $this->toNodes($data, $params);
		} catch(Exception $e) {
			throw new RuntimeException($e->getMessage());
		}

		return $list;
	}

	function toNodes($data, $params = array())
	{
		if(!is_array($data))
		{
			throw new InvalidArgumentException('Invalid data.');
		}

		if(isset($data[$this->name]))
		{
			$data = array($data);
		}

		$nodes = array();

		foreach($data as $item)
		{
			try {
				$nodes[] = $this->toNode($item, $params);
			} catch(Exception $e) {
				throw new RuntimeException($e->getMessage());
			}
		}

		return $nodes;
	}

	function beforeSave()
	{
		App::import('Sanitize');

		foreach($this->_schema as $field => $definition)
		{
			if(isset($this->data[$this->name][$field]))
			{
				if(isset($this->html) && in_array($field, $this->html))
				{
					$this->data[$this->name][$field] = $this->xss($this->data[$this->name][$field]);
					continue;
				}

				$this->data[$this->name][$field] = Sanitize::html($this->data[$this->name][$field], true);

				if(!empty($this->data[$this->name][$field]) && in_array($field, array('title', 'name', 'label')) && !preg_match('/[A-Za-z0-9]/', $this->data[$this->name][$field]))
				{
					$this->invalidate($field, 'Field must contain alphanumeric characters.');
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Executes after save() is performed
	 *
	 * @param boolean $created True if the save created a new record
	 *
	 * @return boolean
	 */
	function afterSave($created)
	{
		$model = $this->name;
		$id = $this->id;
		$luceneIndexPath = TMP . DS . 'index';

		$index = Zend_Search_Lucene::open($luceneIndexPath);

		$plugin = 'core';
		if(isset($this->plugin))
		{
			$plugin = $this->plugin;
		}

		if(!$created) // the record is an update of a pre-existing record
		{
			$hits = $index->find('recordId:' . $plugin . '_' . $model . '_' . $id);

			foreach($hits as $hit)
			{
			    $index->delete($hit->id);
			}	
		}

		$this->data[$this->name]['id'] = $id;

		$ldoc = $this->_toLuceneDoc($plugin, $model, $model, $this->data);

		if(isset($ldoc))
		{
			$index->addDocument($ldoc);
		}

		return true;
	}

	/**
	 * Executes after delete() is performed
	 *
	 * @return boolean
	 */
	function afterDelete()
	{
		$model = $this->name;
		$id = $this->id;
		$luceneIndexPath = TMP . DS . 'index';

		$index = Zend_Search_Lucene::open($luceneIndexPath);

		$plugin = 'core';
		if(isset($this->plugin))
		{
			$plugin = $this->plugin;
		}

		$hits = $index->find('recordId:' . $plugin . '_' . $model . '_' . $id);

		foreach($hits as $hit)
		{
			$index->delete($hit->id);
		}

		return true;
	}

	/**
	 * Converts a interest name to a keyword
	 *
	 * @param string $name Interest Name
	 *
	 * @return string Interest Keyword
	 */
	function toKeyword($name)
	{
		if(empty($name))
		{
			throw new InvalidArgumentException('Invalid Name');
		}

		if(!is_scalar($name))
		{
			throw new InvalidArgumentException('Invalid Name');
		}

		$patterns = array(
			'/[^\w]/',
			'/_/',
		);

		return strtolower(preg_replace($patterns, '', $name));
	}

	/**
	 * Formats Bytes into User Readable Sizes
	 *
	 * @param integer $bytes Bytes
	 *
	 * @throws InvalidArgumentException
	 *
	 * @return string Size
	 */
	function formatSize($bytes)
	{
		if(empty($bytes) || !is_numeric($bytes))
		{
			throw new InvalidArgumentException('Invalid size');
		}

		$bytes = abs($bytes);

		if($bytes >= 1073741824)
		{
			$bytes = number_format($bytes / 1073741824, 2) . ' GB';
		}
		else if($bytes >= 1048576)
		{
			$bytes = number_format($bytes / 1048576, 2) . ' MB';
		}
		else if($bytes >= 1)
		{
			$bytes = number_format($bytes / 1024, 2) . ' KB';
		}
		else if($bytes == 0)
		{
			$bytes = '0 KB';
		}

		return $bytes;
	}

	/**
	 * Strips out XSS elements from HTML
	 *
	 * @param string $html HTML
	 *
	 * @throws InvalidArgumentException
	 * @throws RuntimeException
	 *
	 * @return string Stripped HTML
	 */
	function xss($html)
	{
		if(!empty($html))
		{
			if(!is_string($html))
			{
				throw new InvalidArgumentException('Invalid HTML');
			}
		}

		$stripped = null;
		try {
			$stripped = $this->allow_attrs($this->allow_tags($html));
		} catch(Exception $e) {
			throw new RuntimeException($e);
		}

		return $stripped;
	}

	/** 
	 * Strips invalid tags from HTML
	 *
	 * @param string $html HTML
	 *
	 * @throws InvalidArgumentException
	 *
	 * @return string Stripped HTML
	 */
	function allow_tags($html)
	{
		if(!empty($html))
		{
			if(!is_string($html))
			{
				throw new InvalidArgumentException('Invalid HTML');
			}
		}

		$tags = array('a', 'abbr', 'acronym', 'address', 'area', 'b', 'big', 'blockquote', 'br', 'caption', 'center', 'cite', 'code', 'col', 'colgroup', 'dd', 'del', 'dfn', 'div', 'dl', 'dt', 'em', 'font', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'hr', 'i', 'img', 'ins', 'li', 'map', 'ol', 'p', 'pre', 'q', 's', 'samp', 'small', 'span', 'strike', 'strong', 'style', 'sub', 'sup', 'table', 'tbody', 'td', 'tfoot', 'th', 'thead', 'tr', 'tt', 'u', 'ul', 'var');

		return strip_tags($html, '<' . join('><', $tags) . '>');
	}

	/**
	 * Strips invalid attributes from HTML
	 *
	 * @param string $html HTML
	 *
	 * @throws InvalidArgumentException
	 *
	 * @return string Stripped HTML
	 */
	function allow_attrs($html)
	{
		if(!empty($html))
		{
			if(!is_string($html))
			{
				throw new InvalidArgumentException('Invalid HTML');
			}
		}

		$attrs = array('abbr', 'accesskey', 'align', 'alt', 'axis', 'bgcolor', 'border', 'cellpadding', 'cellspacing', 'char', 'charoff', 'charset', 'cite', 'class', 'color', 'colspan', 'compact', 'coords', 'datetime', 'dir', 'face', 'frame', 'headers', 'height', 'href', 'hreflang', 'hspace', 'id', 'ismap', 'lang', 'longdesc', 'media', 'name', 'nohref', 'noshade', 'nowrap', 'rel', 'rev', 'rowspan', 'rules', 'scope', 'shape', 'size', 'span', 'src', 'start', 'style', 'summary', 'target', 'title', 'type', 'usemap', 'valign', 'value', 'vspace', 'width', 'xml:lang');

		$regex = '(?<!' . join(")(?<!", $attrs) . ')';
		return preg_replace_callback('/<[^>]*>/i', create_function('$matches', 'return preg_replace("/ [^ =]*' . $regex . '=(\"[^\"]*\"|\'[^\']*\')/i", "", $matches[0]);'), $html);
	}

	/**
	 * Converts Model Data to a Lucene Document
	 *
	 * @internal
	 *
	 * @param string $plugin Plugin
	 * @param string $model  Model
	 * @param string $alias  Alias
	 * @param string $data   Model Data
	 * @param string $doc    Lucene Doc to Add To
	 *
	 * @throws InvalidArgumentException
	 * @throws RuntimeException
	 *
	 * @return object Lucene Document
	 */
	function _toLuceneDoc($plugin, $model, $alias, $data, $doc = null)
	{
		if(empty($plugin))
		{
			throw new InvalidArgumentException('Invalid Plugin');
		}

		if(!is_string($plugin))
		{
			throw new InvalidArgumentException('Invalid Plugin');
		}

		if(empty($model))
		{
			throw new InvalidArgumentException('Invalid Model');
		}

		if(!is_string($model))
		{
			throw new InvalidArgumentException('Invalid Model');
		}

		if(empty($alias))
		{
			throw new InvalidArgumentException('Invalid Alias');
		}

		if(!is_string($alias))
		{
			throw new InvalidArgumentException('Invalid Alias');
		}

		if(empty($data))
		{
			throw new InvalidArgumentException('Invalid Data');
		}

		if(!is_array($data))
		{
			throw new InvalidArgumentException('Invalid Data');
		}

		if(!empty($doc))
		{
			if(!is_object($doc))
			{
				throw new InvalidArgumentException('Invalid Document');
			}
		}

		if(isset($data[$alias]))
		{
			$data = $data[$alias];
		}

		$modelObj =& $this;
		if($model != $this->name)
		{
			if(!isset($this->$model))
			{
				throw new RuntimeException('Unable to find model');
			}

			$modelObj =& $this->$model;
		}

		if(empty($doc))
		{
			$doc = new Zend_Search_Lucene_Document();
		}

		$doc->addField(Zend_Search_Lucene_Field::UnIndexed('plugin', $plugin));
		$doc->addField(Zend_Search_Lucene_Field::UnIndexed('model', $model));
		$doc->addField(Zend_Search_Lucene_Field::Keyword('recordId', $plugin . '_' . $model . '_' . $data['id']));

		foreach($modelObj->_schema as $field => $definition)
		{
			if(!isset($data[$field]))
			{
				$data[$field] = '';
			}

			if(isset($modelObj->noindex) && in_array($field, $modelObj->noindex))
			{
				continue;
			}

			if(preg_match('/enum/', $definition['type']))
			{
				$definition['type'] = 'enum';
			}

			if(!method_exists($this, $definition['type'] . 'ToField'))
			{
				continue;
			}

			try {
				$searchField = call_user_func(array($this, $definition['type'] . 'ToField'), $alias, $field, $data[$field]);
			} catch(Exception $e) {
				continue;
			}

			if(!empty($searchField))
			{
				$doc->addField($searchField);
			}
		}

		foreach($modelObj->belongsTo as $alias => $definition)
		{
			if(!isset($data[$alias]))
			{
				continue;
			}	

			$belongsTo = $alias;
			if(isset($modelObj->belongsTo[$alias]['className']))
			{
				$belongsTo = $modelObj->belongsTo[$alias]['className'];
			}

			try {
				$doc = $this->_toLuceneDoc($plugin, $belongsTo, $alias, $data, $doc);
			} catch(Exception $e) {
				continue;
			}
		}

		foreach($modelObj->hasOne as $alias => $definition)
		{
			if(!isset($data[$alias]))
			{
				continue;
			}	

			$hasOne = $alias;
			if(isset($modelObj->hasOne[$alias]['className']))
			{
				$hasOne = $modelObj->hasOne[$alias]['className'];
			}

			try {
				$doc = $this->_toLuceneDoc($plugin, $hasOne, $alias, $data, $doc);
			} catch(Exception $e) {
				continue;
			}
		}

		foreach($modelObj->hasMany as $alias => $definition)
		{
			if(!isset($data[$alias]))
			{
				continue;
			}	

			$hasMany = $alias;
			if(isset($modelObj->hasMany[$alias]['className']))
			{
				$hasMany = $modelObj->hasMany[$alias]['className'];
			}

			try {
				$doc = $this->_toLuceneDoc($plugin, $hasMany, $alias, $data, $doc);
			} catch(Exception $e) {
				continue;
			}
		}

		foreach($modelObj->hasAndBelongsToMany as $alias => $definition)
		{
			if(!isset($data[$alias]))
			{
				continue;
			}	

			$hasAndBelongsToMany = $alias;
			if(isset($modelObj->hasAndBelongsToMany[$alias]['className']))
			{
				$hasAndBelongsToMany = $modelObj->hasAndBelongsToMany[$alias]['className'];
			}

			try {
				$doc = $this->_toLuceneDoc($plugin, $hasAndBelongsToMany, $alias, $data, $doc);
			} catch(Exception $e) {
				continue;
			}
		}

		return $doc;
	}

	/**
	 * Creates a Zend_Search_Lucene_Field for an Integer
	 *
	 * @internal
	 *
	 * @param string  $model Model
	 * @param string  $field Field
	 * @param integer $value Value
	 *
	 * @throws InvalidArgumentException
	 *
	 * @return object Zend_Search_Lucene_Field
	 */
	function _integerToField($model, $field, $value)
	{
		if(empty($model))
		{
			throw new InvalidArgumentException('Invalid Model');
		}

		if(!is_string($model))
		{
			throw new InvalidArgumentException('Invalid Model');
		}

		if(empty($field))
		{
			throw new InvalidArgumentException('Invalid Field');
		}

		if(!is_string($field))
		{
			throw new InvalidArgumentException('Invalid Field');
		}

		if(!empty($value))
		{
			if(!is_numeric($value))
			{
				throw new InvalidArgumentException('Invalid Value');
			}
		}

		if($field == 'id')
		{
			return Zend_Search_Lucene_Field::Text($model . '_' . $field, $value);
		}
		else
		{
			return Zend_Search_Lucene_Field::UnIndexed($model . '_' . $field, $value);
		}
	}

	/**
	 * Creates a Zend_Search_Lucene_Field for Text
	 *
	 * @internal
	 *
	 * @param string $model Model
	 * @param string $field Field
	 * @param string $value Value
	 *
	 * @throws InvalidArgumentException
	 *
	 * @return object Zend_Search_Lucene_Field
	 */
	function _textToField($model, $field, $value)
	{
		if(empty($model))
		{
			throw new InvalidArgumentException('Invalid Model');
		}

		if(!is_string($model))
		{
			throw new InvalidArgumentException('Invalid Model');
		}

		if(empty($field))
		{
			throw new InvalidArgumentException('Invalid Field');
		}

		if(!is_string($field))
		{
			throw new InvalidArgumentException('Invalid Field');
		}

		if(!empty($value))
		{
			if(!is_scalar($value))
			{
				throw new InvalidArgumentException('Invalid Value');
			}
		}

		return $this->_stringToField($model, $field, $value);
	}

	/**
	 * Creates a Zend_Search_Lucene_Field for a String
	 *
	 * @internal
	 *
	 * @param string $model Model
	 * @param string $field Field
	 * @param string $value Value
	 *
	 * @throws InvalidArgumentException
	 *
	 * @return object Zend_Search_Lucene_Field
	 */
	function _stringToField($model, $field, $value)
	{
		if(empty($model))
		{
			throw new InvalidArgumentException('Invalid Model');
		}

		if(!is_string($model))
		{
			throw new InvalidArgumentException('Invalid Model');
		}

		if(empty($field))
		{
			throw new InvalidArgumentException('Invalid Field');
		}

		if(!is_string($field))
		{
			throw new InvalidArgumentException('Invalid Field');
		}

		if(!empty($value))
		{
			if(!is_scalar($value))
			{
				throw new InvalidArgumentException('Invalid Value');
			}
		}

		if(isset($this->store[$model]) && in_array($field, $this->store[$model]))
		{
			return Zend_Search_Lucene_Field::Text($model . '_' . $field, $value);
		}
		else
		{
			return Zend_Search_Lucene_Field::UnIndexed($model . '_' . $field, $value);
		}
	}

	/**
	 * Creates a Zend_Search_Lucene_Field for a DateTime
	 *
	 * @internal
	 *
	 * @param string $model Model
	 * @param string $field Field
	 * @param string $value Value
	 *
	 * @throws InvalidArgumentException
	 *
	 * @return object Zend_Search_Lucene_Field
	 */
	function _datetimeToField($model, $field, $value)
	{
		if(empty($model))
		{
			throw new InvalidArgumentException('Invalid Model');
		}

		if(!is_string($model))
		{
			throw new InvalidArgumentException('Invalid Model');
		}

		if(empty($field))
		{
			throw new InvalidArgumentException('Invalid Field');
		}

		if(!is_string($field))
		{
			throw new InvalidArgumentException('Invalid Field');
		}

		if(!empty($value))
		{
			if(!is_scalar($value))
			{
				throw new InvalidArgumentException('Invalid Value');
			}
		}

		return Zend_Search_Lucene_Field::UnIndexed($model . '_' . $field, strtotime($value));
	}

	/**
	 * Creates a Zend_Search_Lucene_Field for an Enum
	 *
	 * @internal
	 *
	 * @param string $model Model
	 * @param string $field Field
	 * @param string $value Value
	 *
	 * @throws InvalidArgumentException
	 *
	 * @return object Zend_Search_Lucene_Field
	 */
	function _enumToField($model, $field, $value)
	{
		if(empty($model))
		{
			throw new InvalidArgumentException('Invalid Model');
		}

		if(!is_string($model))
		{
			throw new InvalidArgumentException('Invalid Model');
		}

		if(empty($field))
		{
			throw new InvalidArgumentException('Invalid Field');
		}

		if(!is_string($field))
		{
			throw new InvalidArgumentException('Invalid Field');
		}

		if(!empty($value))
		{
			if(!is_scalar($value))
			{
				throw new InvalidArgumentException('Invalid Value');
			}
		}

		return Zend_Search_Lucene_Field::Keyword($model . '_' . $field, $value);
	}

	/**
	 * Creates a Zend_Search_Lucene_Field for a Boolean
	 *
	 * @internal
	 *
	 * @param string  $model Model
	 * @param string  $field Field
	 * @param boolean $value Value
	 *
	 * @throws InvalidArgumentException
	 *
	 * @return object Zend_Search_Lucene_Field
	 */
	function _booleanToField($model, $field, $value)
	{
		if(empty($model))
		{
			throw new InvalidArgumentException('Invalid Model');
		}

		if(!is_string($model))
		{
			throw new InvalidArgumentException('Invalid Model');
		}

		if(empty($field))
		{
			throw new InvalidArgumentException('Invalid Field');
		}

		if(!is_string($field))
		{
			throw new InvalidArgumentException('Invalid Field');
		}

		if(!empty($value))
		{
			if(!is_bool($value))
			{
				throw new InvalidArgumentException('Invalid Value');
			}
		}

		return Zend_Search_Lucene_Field::UnIndexed($model . '_' . $field, (($value) ? 'True' : 'False'));
	}
}
?>
