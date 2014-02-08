<?php
/**
 * Response for REST API calls.
 *
 * @package   api/http
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-api-import
 * @link      http://fr.slideshare.net/developers/documentation
 * @copyright 2014 Spoon
 *
 * @since    1.0.0
 */
class SlideShareHttpResponse
{	
	private $data;
	
	/**
	 * Constructor
	 *
	 * @param mixed $data The service response data
 	 *
 	 * @since    1.0.0
	 */
	public function __construct($data) 
	{
		$this->data = utf8_encode($data);
	}
	
	/**
	 * Parse XML response from SlideShare API
	 *
	 * @return SlideShareModel|false
	 * @throws SlideShareException, SlideShareServiceException
 	 *
 	 * @since    1.0.0
	 */
	public function parse()
	{
		if($this->data) {
			if($this->data instanceof Exception) {
				throw $this->data;
			} else {
				$parser = new SlideShareXMLParser($this->data);
				
				try {
					$result = $parser->parse();
					
					if($result instanceof SlideShareModel) {
						return $result;
					}
				} catch(SlideShareServiceException $exception) {
					throw $exception;
				} catch(SlideShareException $exception) {
					throw $exception;
				}
			}
		}
		throw new SlideShareException(__("SlideShare HTTP error on received data"), $this->data);
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
}