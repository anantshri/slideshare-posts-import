<?php
/**
 * Response for REST API calls.
 *
 * @package   SlideShareAPIResponse
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-api-import
 * @link      http://fr.slideshare.net/developers/documentation
 * @copyright 2014 Spoon
 *
 * @since    1.0.0
 */
class SlideShareAPIResponse
{	
	private $data;
	private $error;
	
	private $headers = array();
	private $body;
	
	/**
	 * Constructor
	 *
	 * @param mixed $data The service response data
 	 *
 	 * @since    1.0.0
	 */
	public function __construct($data) 
	{
		$this->data = $data;
	}
}