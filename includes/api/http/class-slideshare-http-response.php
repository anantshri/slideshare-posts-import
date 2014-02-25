<?php
/**
 * Response for REST API calls.
 *
 * @package   SlideshareHttpResponse
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-api-import
 * @see       http://fr.slideshare.net/developers/documentation
 * @copyright 2014 Spoon
 *
 * @since    1.0.0
 */
class SlideshareHttpResponse
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
		$this->data = $data;
	}
	
	/**
	 * Parse XML response from Slideshare API
	 *
	 * @return SlideshareModel|false
	 *
	 * @throws SlideshareHttpException
	 * @throws SlideshareParserException
	 * @throws SlideshareException
	 * @throws SlideshareServiceException
 	 *
 	 * @since    1.0.0
	 */
	public function parse()
	{
		if(!$this->data) {
			throw new SlideshareHttpException(__("Slideshare HTTP error on received data"), $this->data);
		} else {
			if($this->data instanceof Exception) {
				throw $this->data;
			} else {
				$parser = new SlideshareXMLParser(utf8_encode($this->data));
				
				try {
					$result = $parser->parse();
					
					if($result instanceof SlideshareModel) {
						return $result;
					}
				} catch(SlideshareParserException $exception) {
					throw $exception;
				} catch(SlideshareServiceException $exception) {
					throw $exception;
				} catch(SlideshareException $exception) {
					throw $exception;
				}
			}
		}
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