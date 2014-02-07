<?php
/**
 * User object model.
 *
 * @package   User
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-api-import
 * @copyright 2014 Spoon
 *
 * @since    1.0.0
 */	
class User
{
	private $name;
	private $count;
	private $slideshows = array();
	
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

		foreach($xml->children() as $child) {
			switch((string) $child->getName()) {
				case 'Name':  $this->name = (string) $child; break;
				case 'Count': $this->count = $value; break;
				case 'Slideshow': 
					foreach($child->Slideshow as $slideshowXML) {
						$slideshow = new Slideshows();
						$this->slideshows[] = $slideshow->loadFromXML($slideshowXML);
					}
					break;
				default:
			}
		}
		return $this;
	}
}