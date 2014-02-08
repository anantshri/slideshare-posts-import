<?php
/**
 * Error service response exception.
 *
 * @package   api/exceptions
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-api-import
 * @copyright 2014 Spoon
 *
 * @since    1.0.0
 */

/**
 * Example:
 *
 * <SlideShareServiceError>
 *   <Message ID="10">User Not Found</Message>
 * </SlideShareServiceError>
 */
class SlideShareServiceException extends ErrorException
{	
	// private $id;
	// private $message;
	// 
	// /**
	//  * Constructor
	//  *
	//  * @param string $id The error ID
	//  * @param string $message The error message
	//  	 *
	//  	 * @since    1.0.0
	//  */
	// public function __construct($id, $message)
	// {
	// 	$this->id = $id;
	// 	$this->message = $message;
	// }
	// 
	// /**
	//  * Get ID
	//  *
	//  * @return string The error ID
	//  	 *
	//  	 * @since    1.0.0
	//  */
	// public function getId()
	// {
	// 	return $this->id;
	// }
	// 
	// /**
	//  * Get message
	//  *
	//  * @return string The error message
	//  	 *
	//  	 * @since    1.0.0
	//  */
	// public function getMessage()
	// {
	// 	return $this->message;
	// }
}