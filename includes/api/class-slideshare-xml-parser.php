<?php
/**
 * API response XML parser.
 *
 * @package   SlideShareXMLParser
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-api-import/includes/api/class-slideshare-xml-parser.php
 * @copyright 2014 Spoon
 *
 * @since    1.0.0
 */
class SlideShareXMLParser
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
	 * Parse data to instanciate the appropriate SlideShareModel class
	 *
	 * @return SlideshareModel|null
	 * @throws SlideShareException, SlideShareServiceException
	 */
	public function parse()
	{
		switch($this->xml->getName()) {
			case 'User':
				$object = new User();
				return $object->loadFromXML($this->xml);
			case 'Slideshow':
				$object = new Slideshow();
				return $object->loadFromXML($this->xml);
			case 'SlideShareServiceError':
				$error = $this->xml->Message;
				throw new SlideShareServiceException((string) $error, (int) $error['ID']);
			default:
				throw new SlideShareParserException('Unknown node name', 'SlideShare response XML parsing failed ! This node name is unknown.');
		}
	}
}
