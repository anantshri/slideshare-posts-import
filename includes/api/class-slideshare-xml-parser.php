<?php
/**
 * API response XML parser.
 *
 * @package   SlideshareXMLParser
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-api-import
 * @copyright 2014 Spoon
 *
 * @since    1.0.0
 */
class SlideshareXMLParser
{	
	/**
	 * @var SimpleXMLElement
	 */
	private $xml;
	
	/**
	 * Constructor
	 *
	 * @param string $data The data to parse
	 */
	public function __construct($data)
	{
		$this->xml = new SimpleXMLElement($data);
	}
	
	/**
	 * Parse data to instanciate the appropriate SlideshareModel class
	 *
	 * @return SlideshareModel|null
	 * @throws SlideshareException, SlideshareServiceException
	 */
	public function parse($object = null)
	{
		switch($this->xml->getName()) {
			case 'User':
                if(null === $object)
                    $object = new User();
				return $object->loadFromXML($this->xml);
			case 'Slideshow':
                if(null === $object)
                    $object = new Slideshow();
				return $object->loadFromXML($this->xml);
			case 'SlideShareServiceError':
				$error = $this->xml->Message;
				throw new SlideshareServiceException((string) $error, (int) $error['ID']);
			default:
				throw new SlideshareParserException('Unknown node name', 'Slideshare response XML parsing failed ! This node name is unknown.');
		}
	}
}
