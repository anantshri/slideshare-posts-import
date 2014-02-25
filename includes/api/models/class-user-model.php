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
class User extends SlideshareModel
{
	private $name;
	private $count;
	private $slideshows = array();
	
	/**
	 * Constructor
 	 *
 	 * @since    1.0.0
	 */
	public function __construct()
	{
		parent::__construct('slideshare_');
	}
	
	/**
	 * Load object data from XML
	 *
	 * @param SimpleXMLElement The XML representation of the user
	 * @return User The filled User object
 	 *
 	 * @since    1.0.0
	 */
	public function loadFromXML(SimpleXMLElement $xml)
	{
		foreach($xml->children() as $child) {
			switch((string) $child->getName()) {
				case 'Name':  $this->name = (string) $child; break;
				case 'Count': $this->count = (int) $child; break;
				case 'Slideshow': 
					$slideshow = new Slideshow();
					$this->slideshows[] = $slideshow->loadFromXML($child);
					break;
				default:
					throw new SlideshareParserException('Unknown node name', "Slideshare response XML parsing failed ! Unknown User's XML child '".$child->getName()."'");
			}
		}
		return $this;
	}
	
	/**
	 * Return all available metadata keys for WordPress posts.
	 *
	 * @return array The array of available metadata keys.
 	 *
 	 * @since    1.0.0
	 */
	public function getAvailableMetadata()
	{
		$metadata = array();
		$properties = array_keys(get_object_vars($this));

		foreach($properties as $property) {
		    $metadata[] = $this->generateMetadataKey($property);
		}
		return $metadata;
	}
	
	/**
	 * Get name
	 *
	 * @return string The name
 	 *
 	 * @since    1.0.0
	 */
	public function getName()
	{
		return $this->name;
	}
	
	/**
	 * Get slideshows count
	 *
	 * @return integer The count of slideshows
 	 *
 	 * @since    1.0.0
	 */
	public function getCount()
	{
		return $this->count;
	}
	
	/**
	 * Get slideshows
	 *
	 * @return array The slideshows
 	 *
 	 * @since    1.0.0
	 */
	public function getSlideshows()
	{
		return $this->slideshows;
	}
}