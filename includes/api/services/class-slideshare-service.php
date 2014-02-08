<?php
/**
 * Abstract class for REST API calls.
 *
 * @abstract
 *
 * @package   api/services
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-api-import
 * @copyright 2014 Spoon
 *
 * @since    1.0.0
 */
abstract class SlideShareService
{
	protected $credentials;
	
	public function __construct($apiKey, $sharedSecret) 
	{
		$this->credentials = new SlideShareCredentials($apiKey, $sharedSecret);
	}
	
	public function getUsername()
	{
		return $this->credentials->getUsername();
	}
	
	public function getPassword()
	{
		return $this->credentials->getPassword();
	}
}
	