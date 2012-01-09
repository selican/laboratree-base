<?php
class ImageComponent extends Object
{
	/**
	 * Crops an Image to the Destination Height and Width.
	 * 
	 * @param string  $filename   Filename
	 * @param integer $max_height Destination Height
	 * @param integer $max_width  Destination Width
	 *
	 * @throws InvalidArgumentException
	 * @throws RuntimeException
	 * 
	 * @return mixed Image Data
	 */
	function crop($filename, $max_height, $max_width)
	{
		if(empty($filename) || !is_string($filename))
		{
			throw new InvalidArgumentException('Invalid filename.');
		}

		if(!file_exists($filename))
		{
			throw new InvalidArgumentException('Invalid filename.');
		}

		if(!is_numeric($max_height) || $max_height < 1)
		{
			throw new InvalidArgumentException('Invalid Max Height.');
		}

		if(!is_numeric($max_width) || $max_width < 1)
		{
			throw new InvalidArgumentException('Invalid Max Width.');
		}

		if(($imagesize = getimagesize($filename)) === false)
		{
			throw new InvalidArgumentException('Invalid filename.');
		}
	
		list($width, $height, $type) = $imagesize;
                $ratio = $height / $width;
		$target_ratio = $max_height / $max_width;

		$start_x = 0;
		$end_x = $width;
		$start_y = 0;
		$end_y = $height;

		$new_height = $height;
		$new_width = $width;

                if($ratio < $target_ratio)
                {
                        $new_width = $height / $target_ratio;
			$start_x = round(($width - $new_width) / 2);
			$end_x = $width - $start_x;
                }
                else if($ratio > $target_ratio)
                {
                        $new_height = $width * $target_ratio;
			$start_y = round(($height - $new_height) / 2);
			$end_y = $height - $start_y;
                }

		if(($cropped = imagecreatetruecolor($new_width, $new_height)) === false)
		{
			throw new RuntimeException('Unable to create truecolor image.');
		}
		if(($resized = imagecreatetruecolor($max_width, $max_height)) === false)
		{
			throw new RuntimeException('Unable to create truecolor image.');
		}

		switch($type)
		{
			case 1:
				$source = imagecreatefromgif($filename);
				break;
			case 2:
				$source = imagecreatefromjpeg($filename);
				break;
			case 3:
				$source = imagecreatefrompng($filename);
				break;
			case 6:
				$source = imagecreatefromwbmp($filename);
				break;
			case 15:
				$source = imagecreatefromwbmp($filename);
				break;
			default:
				$source = imagecreatetruecolor($max_width, $max_height);
		}

		if($source === false)
		{
			throw new RuntimeException('Unable to create source image.');
		}

		if(!imagecopy($cropped, $source, 0, 0, $start_x, $start_y, $new_width, $new_height))
		{
			throw new RuntimeException('Unable to copy image.');
		}
		if(!imagecopyresampled($resized, $cropped, 0, 0, 0, 0, $max_width, $max_height, $new_width, $new_height))
		{
			throw new RuntimeException('Unable to resample image.');
		}

		if(!ob_start())
		{
			throw new RuntimeException('Unable to capture output.');
		}

		imagepng($resized);
		$image_data = ob_get_contents();
		if(!ob_end_clean())
		{
			throw new RuntimeException('Unable to close output.');
		}

		return $image_data;
	}

	/**
	 * Proportionatly Resizes an Image to the Destination Height or Width.
	 * 
	 * @param string $filename   Filename
	 * @param mixed  $max_height Destination Height or 'auto'
	 * @param mixed  $max_width  Destination Width or 'auto'
	 *
	 * @throws InvalidArgumentException
	 * @throws RuntimeException
	 *
	 * @return mixed Image Data
	*/
	function scale($filename, $max_height='auto', $max_width='auto')
	{
		if(empty($filename) || !is_string($filename))
		{
			throw new InvalidArgumentException('Invalid filename.');
		}

		if(!file_exists($filename))
		{
			throw new InvalidArgumentException('Invalid filename.');
		}

		if($max_height != 'auto' && (!is_numeric($max_height) || $max_height < 1))
		{
			throw new InvalidArgumentException('Invalid Max Height.');
		}

		if($max_width != 'auto' && (!is_numeric($max_width) || $max_width < 1))
		{
			throw new InvalidArgumentException('Invalid Max Width.');
		}

		if($max_height == 'auto' && $max_width == 'auto')
		{
			throw new InvalidArgumentException('Invalid Max Values.');
		}

		if(($imagesize = getimagesize($filename)) === false)
		{
			throw new InvalidArgumentException('Invalid filename.');
		}

		list($width, $height, $type) = $imagesize;
		$ratio = $height / $width;

		if($max_width == 'auto')
		{
			$max_width = max(1, round($max_height / $ratio, 0));
		}
		else if($max_height == 'auto')
		{
			$max_height = max(1, round($max_width * $ratio, 0));
		}

		if(($resized = imagecreatetruecolor($max_width, $max_height)) === false)
		{
			throw new RuntimeException('Unable to create truecolor image.');
		}

		switch($type)
		{
			case 1:
				$source = imagecreatefromgif($filename);
				break;
			case 2:
				$source = imagecreatefromjpeg($filename);
				break;
			case 3:
				$source = imagecreatefrompng($filename);
				break;
			case 6:
				$source = imagecreatefromwbmp($filename);
				break;
			case 15:
				$source = imagecreatefromwbmp($filename);
				break;
			default:
				$source = imagecreatetruecolor($max_width, $max_height);
		}

		if($source === false)
		{
			throw new RuntimeException('Unable to create source image.');
		}

		if(!imagecopyresampled($resized, $source, 0, 0, 0, 0, $max_width, $max_height, $width, $height))
		{
			throw new RuntimeException('Unable to resample image.');
		}

		if(!ob_start())
		{
			throw new RuntimeException('Unable to capture output.');
		}
		imagepng($resized);
		$image_data = ob_get_contents();
		if(!(ob_end_clean()))
		{
			throw new RuntimeException('Unable to close output.');
		}

		return $image_data;
	}

	/**
	 * Generates User Images
	 *
	 * @param string $tmpfile Uploaded Filename
	 *
	 * @return string Generated Filename
	 */
	function user($tmpfile)
	{
		if(empty($tmpfile) || !is_string($tmpfile))
		{
			throw new InvalidArgumentException('Invalid tmpfile.');
		}

		if(!file_exists($tmpfile))
		{
			throw new InvalidArgumentException('Invalid filename.');
		}

		/* Determine Mimetype of Image */
		if(($finfo = new finfo(FILEINFO_MIME, '/usr/share/misc/magic')) === false)
		{
			throw new RuntimeException('Unable to open magic file.');
		}

		if(($mimetype = $finfo->file($tmpfile)) === false)
		{
			throw new RuntimeException('Unable to determine mimetype.');
		}

		$extension = '';
		switch($mimetype)
		{
			case 'image/gif':
				$extension = 'gif';
				break;
			case 'image/jpeg':
				$extension = 'jpg';
				break;
			case 'image/png':
				$extension = 'png';
				break;
			case 'image/gif; charset=binary':
				$extension = 'gif';
				break;
			case 'image/jpeg; charset=binary':
				$extension = 'jpg';
				break;
			case 'image/png; charset=binary':
				$extension = 'png';
				break;
			default:
				return false;
		}
	
		/* Generate New Image Filename to Avoid Caching Problems */
		$filename = md5(uniqid('', true));
	
		/* Resize Image to 200 width */
		$destination = IMAGES . 'users/' . $filename . '.png';
		try
		{
			$image = $this->scale($tmpfile, 'auto', 200);
		}
		catch(Exception $e)
		{
			throw new RuntimeException($e->getMessage());
		}

		if(($fp = fopen($destination, 'wb')) === false)
		{
			throw new RuntimeException('Unable to open destination file.');
		}
		if(fwrite($fp, $image) === false)
		{
			throw new RuntimeException('Unable to write to destination file.');
		}
		if(fclose($fp) === false)
		{
			throw new RuntimeException('Unable to close destination file.');
		}

		/* Resize Image to 50 width */
		$destination = IMAGES . 'users/' . $filename . '_thumb.png';
		try
		{
			$image = $this->crop($tmpfile, 50, 50);
		}
		catch(Exception $e)
		{
			throw new RuntimeException($e->getMessage());
		}

		if(($fp = fopen($destination, 'wb')) === false)
		{
			throw new RuntimeException('Unable to open destination file.');
		}

		if(fwrite($fp, $image) === false)
		{
			throw new RuntimeException('Unable to write to destination file.');
		}
		if(fclose($fp) === false)
		{
			throw new RuntimeException('Unable to close destination file.');
		}

		return $filename;
	}
}
?>
