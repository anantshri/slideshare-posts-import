<?php
/**
 * Abstract class for REST API calls.
 *
 * @package   SlideShareService
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-api-import/includes/api/services/class-slideshare-service.php
 * @copyright 2014 Spoon
 *
 * @abstract
 *
 * @since    1.0.0
 */
abstract class SlideShareService
{
	protected $credentials;
	
	/**
	 * Constructor
     *
	 * @param string $apiKey The SlideShare API key
	 * @param string $sharedSecret The SlideShare API shared secret
 	 *
 	 * @since    1.0.0
	 */
	public function __construct($apiKey, $sharedSecret) 
	{
		$this->credentials = new SlideShareCredentials($apiKey, $sharedSecret);
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
		$this->credentials->setUsername($username);
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
		$this->credentials->setPassword($password);
	}
	
	/**
	 * Get the API authentication username
     *
	 * @return string The SlideShare API authentication username
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
	 * @return string The SlideShare API authentication password
 	 *
 	 * @since    1.0.0
	 */
	public function getPassword()
	{
		return $this->credentials->getPassword();
	}
}
	