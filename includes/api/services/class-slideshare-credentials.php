<?php
/**
 * Credentials for REST API calls.
 *
 * @package   api/services
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-api-import
 * @link      http://fr.slideshare.net/developers/documentation
 * @copyright 2014 Spoon
 *
 * @since    1.0.0
 */
class SlideShareCredentials 
{	
	private $api_key;
	private $shared_secret;
	private $username;
	private $password;
	
	public function __construct($apiKey, $sharedSecret, $username = null, $password = null) 
	{
		$this->api_key = $apiKey;
		$this->shared_secret = $sharedSecret;
		$this->username = $username;
		$this->password = $password;
	}
	
	public function getQueryString() 
	{
		$timestamp = time();
		$hash = $this->hash($timestamp);
		$query = "api_key=$this->api_key&hash=$hash&ts=$timestamp";
		
		if($this->username && $this->password)
			$query .= "&username=$this->username&password=$password";
		
		return $query;
	}
	
	public function setUsername($username) 
	{
		$this->username = $username;
	}
	
	public function setPassword($password) 
	{
		$this->password = $password;
	}
	
	public function setUserCredentials($username, $password) 
	{
		$this->setUsername($username);
		$this->setPassword($password);
	}
	
	private function hash($timestamp)
	{
		return sha1($this->shared_secret.$timestamp);
	}
}