<?php
/**
 * Slideshare exception class.
 *
 * @package   SlideshareException
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-api-import
 * @copyright 2014 Spoon
 *
 * @since    1.0.0
 */
class SlideshareException extends Exception
{
    const SLIDESHARE_ERROR_PAGE = 100;
    
	private $label;
	
	/**
	 * Constructor
	 *
	 * @param string|int $label The error label or code
	 * @param string $message The error message
	 * @param int $code The optional error code
	 *
	 * @since    1.0.0
	 */
	public function __construct($label, $message)
	{
		$this->label = $label;
		parent::__construct($message);
	}
	
	/**
	 * Get label
	 *
	 * @return string|int The error label or code
	 *
	 * @since    1.0.0
	 */
	public function getLabel()
	{
		return $this->label;
	}
}