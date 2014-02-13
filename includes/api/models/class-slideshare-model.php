<?php
/**
 * Abstract base model class.
 *
 * @package   SlideShareModel
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-api-import/includes/api/model/class-slideshare-model.php
 * @copyright 2014 Spoon
 *
 * @since    1.0.0
 *
 * @abstract
 */	
abstract class SlideShareModel
{
	protected $metadata_prefix;
	
	/**
	 * Constructor
	 *
	 * @param string Prefix for metadata keys
 	 *
 	 * @since    1.0.0
	 */
	public function __construct($prefix)
	{
		$this->metadata_prefix = $prefix;
	}
	
	/**
	 * Generate metadata key for WordPress posts.
	 * Keys are generated to concat prefix with model property name.
	 * This method is used in children classes to array map class properties.
	 *
	 * @param string $property Property from wich generate key
	 * @return string The generated key
 	 *
 	 * @since    1.0.0
	 */
	protected function generateMetadataKey($property)
	{
		return $this->metadata_prefix . $property;
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
		
		$reflect = new ReflectionClass(get_class($this));
		$properties = $reflect->getProperties(ReflectionProperty::IS_PRIVATE);
		
		foreach($properties as $property) {
		    $property->setAccessible(true);
		    $metadata[] = $this->generateMetadataKey($property->getName());
		}
		return $metadata;
	}
	
	/**
	 * Load object data from XML
	 *
	 * @param SimpleXMLElement The XML representation of the tag
	 * @return object The filled object
 	 *
	 * @abstract
	 *
 	 * @since    1.0.0
	 */
	abstract public function loadFromXML(SimpleXMLElement $xml);
}