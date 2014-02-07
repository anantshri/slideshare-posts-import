<?php
/**
 * Tag object model.
 *
 * @package   Tag
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-api-import
 * @copyright 2014 Spoon
 *
 * @since    1.0.0
 */	
class Tag
{
	private $value;
	private $owner;
	private $count;
	
	/**
	 * Load object data from XML
	 *
	 * @param SimpleXMLElement The XML representation of the tag
	 * @return Tag The filled Tag object
 	 *
 	 * @since    1.0.0
	 */
	public function loadFromXML($xml)
	{
		$this->value = (string) $xml;
		
		foreach($xml->attributes() as $attribute => $value) {
			switch((string) $attribute) {
				case 'Count': $this->count = $value; break;
				case 'Owner': $this->owner = $value; break;
				default:
			}
		}
		return $this;
	}
}