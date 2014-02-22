<?php
/**
 * Credentials for REST API calls.
 *
 * @package   SlideShareCredentials
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-api-import
 * @see       http://fr.slideshare.net/developers/documentation
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
	
	/**
	 * Constructor
     *
	 * @param string $apiKey The SlideShare API key
	 * @param string $sharedSecret The SlideShare API shared secret
	 * @param string $username The SlideShare API authentication username
	 * @param string $password The SlideShare API authentication password
 	 *
 	 * @since    1.0.0
	 */
	public function __construct($apiKey, $sharedSecret, $username = null, $password = null) 
	{
		$this->api_key = $apiKey;
		$this->shared_secret = $sharedSecret;
		$this->username = $username;
		$this->password = $password;
	}
	
	/**
	 * Generate the base query string for API calls, 
	 * with API key, shared secret and, eventually,
	 * username and password if set.
     *
	 * @return string The query string part of API credentials.
 	 *
 	 * @since    1.0.0
	 */
	public function getQueryString() 
	{
		$timestamp = time();
		$hash = $this->hash($timestamp);
		$query = "api_key=$this->api_key&hash=$hash&ts=$timestamp";
		
		if($this->username && $this->password)
			$query .= "&username=$this->username&password=$this->password";
		
		return $query;
	}
	
	/**
	 * Set API authentication username
     *
	 * @param string $username The SlideShare API authentication username
 	 *
 	 * @since    1.0.0
	 */
	public function setUsername($username) 
	{
		$this->username = $username;
	}
	
	/**
	 * Set API authentication password
     *
	 * @param string $password The SlideShare API authentication password
 	 *
 	 * @since    1.0.0
	 */
	public function setPassword($password) 
	{
		$this->password = $password;
	}
	
	/**
	 * Set API authentication username and password
     *
	 * @param string $username The SlideShare API authentication username
	 * @param string $password The SlideShare API authentication password
 	 *
 	 * @since    1.0.0
	 */
	public function setUserCredentials($username, $password) 
	{
		$this->setUsername($username);
		$this->setPassword($password);
	}
	
	/**
	 * Get hash for API calls
     *
	 * @param integer $timestamp The timestamp parameter of the request
	 * @return string The SHA1 encoded hash.
 	 *
 	 * @since    1.0.0
	 */
	private function hash($timestamp)
	{
		return sha1($this->shared_secret.$timestamp);
	}
}
