<?php
/**
 * @package   IXMLParser
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-api-import/includes/api/interface-xml-parser.php
 * @copyright 2014 Spoon
 *
 * @since    1.0.0
 */	
interface IXMLParser
{
	/**
	 * Load object data from XML
	 *
	 * @param SimpleXMLElement The XML representation of the tag
	 * @return object The filled object
 	 *
 	 * @since    1.0.0
	 */
	public function loadFromXML(SimpleXMLElement $xml);
}
