<?php
/**
 * JSON encoder for object with private properties.
 *
 * @package   JSONEncoder
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-api-import/includes/class-json-encoder.php
 * @link      http://stackoverflow.com/questions/6613792/how-do-i-json-encode-private-properties-in-php
 * @copyright 2014 Spoon
 *
 * @since    1.0.0
 */	
class JSONEncoder
{
	/**
	 * Encode data to JSON.
	 *
	 * @param mixed $object The data to encode.
	 * @return string The encoded representation of the data.
	 *
	 * @since    1.0.0
	 */
	public function json_encode($object)
	{
	    return json_encode($this->getFields($object));
	}

	/**
	 * Parse object to get all properties, including private ones.
	 *
	 * @param mixed $classObj The data to encode.
	 * @return array The array of fields.
	 *
	 * @since    1.0.0
	 */
	private function getFields($classObj)
	{
	    $fields = array();
		
		if(is_array($classObj)) {
	         foreach ($classObj as $item){
	             $key = key($classObj);
	             $this->doProperty($fields, $key, $item);
	             next($classObj);
	         }
		} else {
		    $reflect = new ReflectionClass($classObj);
		    $props = $reflect->getProperties();
		
		    foreach($props as $property) {
		        $property->setAccessible(true);
		        $obj = $property->getValue($classObj);
		        $name = $property->getName();
		        $this->doProperty($fields, $name, $obj);
			}
		}

		return $fields;
	}

	/**
	 * Get the value of given property depending of its type.
	 * If the property is an object or an array, its value is recursively parsed too.
	 *
	 * @param array $fields The result array to store fields by ref.
	 * @param string $name The name of the property.
	 * @param mixed $obj The property's owner object.
	 *
	 * @since    1.0.0
	 */
	private function doProperty(&$fields, $name, $obj)
	{
	     if (is_object($obj)) {
	         $fields[$name] = $this->getFields($obj);
	         return;
	     }

	     if (is_array($obj)) {
	         $arrayFields = array();
			 
	         foreach ($obj as $item) {
	             $key = key($obj);
	             $this->doProperty($arrayFields, $key, $item);
	             next($obj);
	         }
	         $fields[$name] = $arrayFields;
	     }
	     else {
	         $fields[$name] = $obj;
		 }
	 }
}