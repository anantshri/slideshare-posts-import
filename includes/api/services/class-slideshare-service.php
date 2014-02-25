<?php
/**
 * Abstract class for REST API calls.
 *
 * @package   SlideshareService
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-api-import
 * @copyright 2014 Spoon
 *
 * @abstract
 *
 * @since    1.0.0
 */
abstract class SlideshareService
{
	protected $credentials;
	
	/**
	 * Constructor
     *
	 * @param string $apiKey The Slideshare API key
	 * @param string $sharedSecret The Slideshare API shared secret
 	 *
 	 * @since    1.0.0
	 */
	public function __construct($apiKey, $sharedSecret) 
	{
		$this->credentials = new SlideshareCredentials($apiKey, $sharedSecret);
	}
	
	/**
	 * Set API authentication username
     *
	 * @param string $username The Slideshare API authentication username
 	 *
 	 * @since    1.0.0
	 */
	public function setUsername($username) 
	{
		$this->credentials->setUsername($username);
	}
	
	/**
	 * Set API authentication password
     *
	 * @param string $password The Slideshare API authentication password
 	 *
 	 * @since    1.0.0
	 */
	public function setPassword($password) 
	{
		$this->credentials->setPassword($password);
	}
	
	/**
	 * Get the API authentication username
     *
	 * @return string The Slideshare API authentication username
 	 *
 	 * @since    1.0.0
	 */
	public function getUsername()
	{
		return $this->credentials->getUsername();
	}
	
	/**
	 * Get the API authentication password
     *
	 * @return string The Slideshare API authentication password
 	 *
 	 * @since    1.0.0
	 */
	public function getPassword()
	{
		return $this->credentials->getPassword();
	}
}
	