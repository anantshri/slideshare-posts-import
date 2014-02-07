<?php
/**
 * Response for REST API calls.
 *
 * @package   SlideShareXMLParser
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-api-import
 * @copyright 2014 Spoon
 *
 * @since    1.0.0
 */
class SlideShareXMLParser
{	
	private $xml;
	/*
	 * Example:
	 *
	 * <SlideShareServiceError>
	 *   <Message ID="10">User Not Found</Message>
	 * </SlideShareServiceError>
	 */
	private $error;
	
	public function __construct($data)
	{
		$this->xml = new SimpleXMLElement($data);
	}
	
	public function parse()
	{
		if(!$this->checkError()) {
			
		} else {
			$error = $this->xml->SlideShareServiceError[0]->Message;
			$this->setError((string) $error['ID'], $error);
			return $this->error;
		}
	}
	
	private function checkError()
	{
		foreach($this->xml->children() as $child) {
			if('SlideShareServiceError' == $child->getName()) {
				return true;
			}
		}
		return false;
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
		$this->error = new stdClass();
		$this->error->code = $code;
		$this->error->message = $message;
	}
}
