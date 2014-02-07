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
	private $data = null;
	private $error = null;
	
	/**
	 * Constructor
	 *
	 * @param mixed $data The service response data
 	 *
 	 * @since    1.0.0
	 */
	public function __construct($data) 
	{
		if($data) {
			if(is_wp_error($data))
				$this->setError($data->get_error_code(), $data->get_error_message());
			else if($data instanceof Exception)
				$this->setError($data->getCode(), $data->getMessage());
			else
				$this->setData(utf8_encode($data));
		}
	}
	
	private function parse()
	{
		
	}
	
	/**
	 * Set response data
	 *
	 * @param mixed $data The service response data
 	 *
 	 * @since    1.0.0
	 */
	private function setData($data)
	{
		$this->error = null;
		$this->data = $data;
	}
	
	/**
	 * Get response data
	 *
	 * @return string The service response data
 	 *
 	 * @since    1.0.0
	 */
	public function getData()
	{
		return $this->data;
	}
	
	/**
	 * Set error data
	 *
	 * @param string $code The error code
	 * @param string $message The error message
 	 *
 	 * @since    1.0.0
	 */
	private function setError($code, $message)
	{
		$this->data = null;
		$this->error = new stdClass();
		$this->error->code = $code;
		$this->error->message = $message;
	}
	
	/**
	 * Get error
	 *
	 * @return stdClass The service error
 	 *
 	 * @since    1.0.0
	 */
	public function getError()
	{
		return $this->error;
	}
	
	/**
	 * Check if response is in error
	 *
	 * @return boolean
 	 *
 	 * @since    1.0.0
	 */
	public function isError()
	{
		return !is_null($this->error);
	}
}