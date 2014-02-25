<?php
/**
 * Tag object model.
 *
 * @package   api/model
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-api-import
 * @copyright 2014 Spoon
 *
 * @since    1.0.0
 */	
class Tag extends SlideshareModel
{
	private $value;
	private $owner;
	private $count;
	
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
	 * @param SimpleXMLElement The XML representation of the tag
	 * @return Tag The filled Tag object
 	 *
 	 * @since    1.0.0
	 */
	public function loadFromXML(SimpleXMLElement $xml)
	{
		$this->value = (string) $xml;
		
		foreach($xml->attributes() as $attribute => $value) {
			switch((string) $attribute) {
				case 'Count': $this->count = (int) $value; break;
				case 'Owner': $this->owner = (string) $value; break;
				default:
					throw new SlideshareParserException('Unknown node name', "Slideshare response XML parsing failed ! Unknown Tag's XML child '".$child->getName()."'");
			}
		}
		return $this;
	}
	
	/**
	 * Get owner
	 *
	 * @return string The owner
 	 *
 	 * @since    1.0.0
	 */
	public function getOwner()
	{
		return $this->owner;
	}
	
	/**
	 * Get tags count
	 *
	 * @return integer The count of tags
 	 *
 	 * @since    1.0.0
	 */
	public function getCount()
	{
		return $this->count;
	}
	
	/**
	 * Get value
	 *
	 * @return string The value of the tag
 	 *
 	 * @since    1.0.0
	 */
	public function getValue()
	{
		return $this->value;
	}
	
	/**
	 * Magic method __toString
	 *
	 * @return string The string representation of the tag
 	 *
 	 * @since    1.0.0
	 */
	public function __toString()
	{
		return $this->value;
	}
}