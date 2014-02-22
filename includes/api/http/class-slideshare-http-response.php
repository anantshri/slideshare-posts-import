<?php
/**
 * Response for REST API calls.
 *
 * @package   SlideShareHttpResponse
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-api-import
 * @see       http://fr.slideshare.net/developers/documentation
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
		$this->data = $data;
	}
	
	/**
	 * Parse XML response from SlideShare API
	 *
	 * @return SlideShareModel|false
	 *
	 * @throws SlideShareHttpException
	 * @throws SlideShareParserException
	 * @throws SlideShareException
	 * @throws SlideShareServiceException
 	 *
 	 * @since    1.0.0
	 */
	public function parse()
	{
		if(!$this->data) {
			throw new SlideShareHttpException(__("SlideShare HTTP error on received data"), $this->data);
		} else {
			if($this->data instanceof Exception) {
				throw $this->data;
			} else {
				$parser = new SlideShareXMLParser(utf8_encode($this->data));
				
				try {
					$result = $parser->parse();
					
					if($result instanceof SlideShareModel) {
						return $result;
					}
				} catch(SlideShareParserException $exception) {
					throw $exception;
				} catch(SlideShareServiceException $exception) {
					throw $exception;
				} catch(SlideShareException $exception) {
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