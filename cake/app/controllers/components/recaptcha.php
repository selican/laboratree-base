<?php 
class RecaptchaComponent extends Object {
	var $is_valid = false;
	var $error = '';
	
	function startup(&$controller)
	{
		$this->controller =& $controller;
		$this->controller->helpers[] = "Recaptcha";
	}
	
	/**
	 * Checks a Recaptcha Response
	 *
	 * @param array $form Form Data
	 *
	 * @throws InvalidArgumentException
	 *
	 * @return boolean Answer Validity
	 */
	function valid($form)
	{
		if(!empty($form))
		{
			if(!is_array($form))
			{
				throw new InvalidArgumentException('Invalid Form Data');
			}
		}

		if(isset($form['recaptcha_challenge_field']) && isset($form['recaptcha_response_field']))
		{
			if($this->recaptcha_check_answer(Configure::read('Recaptcha.private_key'), env('REMOTE_ADDR'), $form['recaptcha_challenge_field'], $form['recaptcha_response_field']) == 0)
			{
				return false;
			}
		}

		if($this->is_valid)
		{
			return true;
		}
		return false;
	}
	
	/**
	  * Calls an HTTP POST function to verify if the user's guess was correct
	  *
	  * @param string $privkey      Private Key
	  * @param string $remoteip     Remote IP
	  * @param string $challenge    Challange
	  * @param string $response     Response
	  * @param array  $extra_params Extra Variables to Post to Server
	  *
	  * @throws InvalidArgumentException
	  * 
	  * @return integer ReCaptchaResponse
	  */
	function recaptcha_check_answer($privkey, $remoteip, $challenge, $response, $extra_params = array())
	{
		if($privkey == null || $privkey == '')
		{
			throw new InvalidArgumentException('Invalid Private Key');
		}
	
		if($remoteip == null || $remoteip == '')
		{
			throw new InvalidArgumentException('Invalid Remote IP');
		}		
			
		//discard spam submissions
		if($challenge == null || strlen($challenge) == 0 || $response == null || strlen($response) == 0) {
				$this->is_valid = false;
				$this->error = 'incorrect-captcha-sol';
				return 0;
		}

		$response = $this->_recaptcha_http_post(Configure::read('Recaptcha.verifyServer'), "/verify",
			array(
				'privatekey' => $privkey,
				'remoteip' => $remoteip,
				'challenge' => $challenge,
				'response' => $response
			) + $extra_params
		);
	
		$answers = explode ("\n", $response [1]);
			
		if(trim($answers [0]) == 'true')
		{
			$this->is_valid = true;
			return 1;
		}
		else
		{
			$this->is_valid = false;
			$this->error = $answers [1];
			return 0;
		}
	}
	
	/**
	 * Submits an HTTP POST to a reCAPTCHA server
	 *
	 * @param string  $host Host
	 * @param string  $path Path
	 * @param array   $data Post Data
	 * @param integer $port Port
	 *
	 * @throws InvalidArgumentException
	 *
	 * @return array Response
	 */
	function _recaptcha_http_post($host, $path, $data, $port = 80)
	{
		if(empty($host))
		{
			throw new InvalidArgumentException('Invalid Host');
		}

		if(!is_string($host))
		{
			throw new InvalidArgumentException('Invalid Host');
		}

		if(empty($path))
		{
			throw new InvalidArgumentException('Invalid Path');
		}

		if(!is_string($path))
		{
			throw new InvalidArgumentException('Invalid Path');
		}

		if(empty($data))
		{
			throw new InvalidArgumentException('Invalid Post Data');
		}

		if(!is_array($data))
		{
			throw new InvalidArgumentException('Invalid Post Data');
		}

		if(!empty($port))
		{
			if(!is_numeric($port) || $port < 1)
			{
				throw new InvalidArgumentException('Invalid Port');
			}
		}

		$req = $this->_recaptcha_qsencode($data);

		$http_request  = "POST $path HTTP/1.0\r\n";
		$http_request .= "Host: $host\r\n";
		$http_request .= "Content-Type: application/x-www-form-urlencoded;\r\n";
		$http_request .= "Content-Length: " . strlen($req) . "\r\n";
		$http_request .= "User-Agent: reCAPTCHA/PHP\r\n";
		$http_request .= "\r\n";
		$http_request .= $req;

		$response = '';
		if(false == ($fs = @fsockopen($host, $port, $errno, $errstr, 10)))
		{
			die('Could not open socket');
		}

		fwrite($fs, $http_request);

		while(!feof($fs))
		{
			$response .= fgets($fs, 1160); // One TCP-IP packet
		}

		fclose($fs);
		$response = explode("\r\n\r\n", $response, 2);

		return $response;
	}
	
	/**
	 * Encodes the given data into a query string format
	 *
	 * @param array $data Post Data
	 *
	 * @throws InvalidArgumentException
	 *
	 * @return string Encoded Request
	 */
	function _recaptcha_qsencode($data)
	{
		if(empty($data))
		{
			throw new InvalidArgumentException('Invalid Post Data');
		}

		if(!is_array($data))
		{
			throw new InvalidArgumentException('Invalid Post Data');
		}

		$req = "";
		foreach($data as $key => $value)
		{
			$req .= $key . '=' . urlencode( stripslashes($value) ) . '&';
		}

		// Cut the last '&'
		$req = substr($req, 0, strlen($req) - 1);

		return $req;
	}
}
?>
