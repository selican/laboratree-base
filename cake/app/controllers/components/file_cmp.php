<?php
/* This class allows us to add a layer
 * between the file upload functions
 * and our code. By doing this, we can
 * override these functions during unit
 * testing.
 */
class FileCmpComponent extends Object
{
	function initialize(&$controller, $settings = array())
	{
		$this->Controller =& $controller;
	}

	function startup(&$controller) {}

	/**
	 * Wrapper for PHP's is_uploaded_file
	 *
	 * @param string $filename Filename
	 *
	 * @throws InvalidArgumentException
	 *
	 * return boolean File Status
	 */
	function is_uploaded_file($filename)
	{
		if(!empty($filename) && !is_string($filename))
		{
			throw new InvalidArgumentException('Invalid filename.');
		}

		return is_uploaded_file($filename);
	}

	/**
	 * Wrapper for PHP's move_uploaded_file
	 *
	 * @param string $filename    Filename
	 * @param string $destination Destination
	 *
	 * @throws InvalidArgumentException
	 * 
	 * @return boolean Move Status
	 */
	function move_uploaded_file($filename, $destination)
	{
		if(empty($filename) || !is_string($filename))
		{
			throw new InvalidArgumentException('Invalid filename.');
		}

		if(empty($destination) || !is_string($destination))
		{
			throw new InvalidArgumentException('Invalid destination.');
		}

		return move_uploaded_file($filename, $destination);
	}

	/**
	 * Generates a File Checksum
	 *
	 * @param string $filename Filename
	 *
	 * @throws InvalidArgumentException
	 *
	 * @return string File Checksum
	 */
	function checksum($filename)
	{
		if(empty($filename) || !is_string($filename))
		{
			throw new InvalidArgumentException('Invalid filename.');
		}

		return hash_file('sha1', $filename);
	}

	/**
	 * Determines a File Mimetype
	 *
	 * @param string $filename Filename
	 *
	 * @throws InvalidArgmentException
	 *
	 * @return string File Mimetype
	 */
	function mimetype($filename)
	{
		if(empty($filename) || !is_string($filename))
		{
			throw new InvalidArgumentException('Invalid filename.');
		}

		$finfo = new finfo(FILEINFO_MIME);
		return $finfo->file($filename);
	}

	/**
	 * Wrapper for PHP's filesize
	 *
	 * @param string $filename Filename
	 *
	 * @throws InvalidArgumentException
	 *
	 * @return integer File Size
	 */
	function filesize($filename)
	{
		if(empty($filename) || !is_string($filename))
		{
			throw new InvalidArgumentException('Invalid filename.');
		}

		return filesize($filename);
	}

	/**
	 * Wrapper for PHP's file_exists
	 *
	 * @param string $filename Filename
	 *
	 * @throws InvalidArgumentException
	 *
	 * @return boolean File Status
	 */
	function exists($filename)
	{
		if(empty($filename) || !is_string($filename))
		{
			throw new InvalidArgumentException('Invalid filename.');
		}

		return file_exists($filename);
	}

	/** 
	 * Wrapper for PHP's unlink
	 *
	 * @param string $filename Filename
	 *
	 * @throws InvalidArgumentException
	 * 
	 * @return boolean File Status
	 */
	function remove($filename)
	{
		if(empty($filename) || !is_string($filename))
		{
			throw new InvalidArgumentException('Invalid filename.');
		}

		return unlink($filename);
	}

	/**
	 * Outputs a File with Download Headers
	 *
	 * @param string  $filename Filename
	 * @param string  $mimetype File Mimetype
	 * @param integer $size     File Size
	 * @param mixed   $data     File Data
	 *
	 * @throws InvalidArgumentException
	 */
	function output($filename, $mimetype, $size, $data)
	{
		if(empty($filename) || !is_string($filename))
		{
			throw new InvalidArgumentException('Invalid filename.');
		}

		if(empty($mimetype) || !is_string($mimetype))
		{
			throw new InvalidArgumentException('Invalid mimetype.');
		}

		if(!empty($size) && !is_numeric($size))
		{
			throw new InvalidArgumentException('Invalid size.');
		}

		if(!empty($data) && !is_string($data))
		{
			throw new InvalidArgumentException('Invalid data.');
		}

		header('Content-Type: ' . addslashes($mimetype));
		header('Content-Length: ' . addslashes($size));
		header('Content-Disposition: attachment; filename="' . addslashes($filename) . '"');
		header('Content-Transfer-Encoding: binary');
		header('Expired: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

		if(ob_get_length())
		{
			ob_clean();
		}
		flush();

		print($data);
	}

	/**
	 * Saves a File
	 *
	 * @param string $filename Filename
	 * @param mixed  $data     File Data
	 *
	 * @throws InvalidArgumentException
	 * @throws RuntimeException
	 * 
	 * @return integer File Size
	 */
	function save($filename, $data)
	{
		if(empty($filename) || !is_string($filename))
		{
			throw new InvalidArgumentException('Invalid filename.');
		}

		if(!empty($data) && !is_string($data))
		{
			throw new InvalidArgumentException('Invalid data.');
		}

		if(($fp = fopen($filename, 'wb')) === false)
		{
			throw new RuntimeException('Unable to open filename:' . $filename);
		}

		if(($size = fwrite($fp, $data)) === false)
		{
			throw new RuntimeException('Unable to write to file.');
		}

		if(!fclose($fp))
		{
			throw new RuntimeException('Unable to close file.');
		}

		return $size;
	}
}
?>
