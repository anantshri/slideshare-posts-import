<?php
/**
 * Response for REST API calls.
 *
 * @package   api
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
	private $error = null;
	
	public function __construct($data)
	{
		$this->xml = new SimpleXMLElement($data);
	}
	
	public function parse()
	{
		if(!$this->checkError()) {
			$error = $this->xml->SlideShareServiceError[0]->Message;
			$this->setError((string) $error['ID'], $error);
			return $this->error;
		} else {
			$object;
			
			switch($this->xml->getName()) {
				case 'User':
					$object = new User();
					break;
				case 'Slideshow':
					$object = new Slideshow();
					break;
				default:
					$object = null;
			}
			
			if(!is_null($object)) {
				return $object->loadFromXML($this->xml);
			}
			return $object;
		}
	}
	
	private function checkError()
	{
		foreach($this->xml->children() as $child) {
			if('SlideShareServiceError' == $child->getName()) {
				return false;
			}
		}
		return true;
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
