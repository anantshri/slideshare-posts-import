<?php
/**
 * Request for REST API calls.
 *
 * @package   SlideShareAPIRequest
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-api-import
 * @link      http://fr.slideshare.net/developers/documentation
 * @copyright 2014 Spoon
 *
 * @since    1.0.0
 */
class SlideShareAPIRequest 
{	
	private $credentials;
	private $service;
	private $parameters = array();
	
	/**
	 * Constructor
	 *
	 * @param string $service The service REST parameter to call
	 * @param SlideShareCredentials $credentials Credential object for API validation
 	 *
 	 * @since    1.0.0
	 */
	public function __construct($service, SlideShareCredentials $credentials) 
	{
		$this->service = $service;
		$this->credentials = $credentials;
	}
	
	/**
	 * Execute a SlideShare API call from service URL.
	 *
	 * @param string $method The request method GET|POST.
	 * @param mixed $data Optional request parameters (used for POST method only).
	 * @param array $headers HTTP request headers.
 	 *
 	 * @since    1.0.0
	 */
	public function send($method, $data = null, $headers = array()) 
	{
		$response = null;
		$api_url = $this->getServiceURL();

		add_filter('https_ssl_verify', '__return_false');
		
		if('post' == strtolower($method)) {
			$response = wp_remote_post($api_url, array(
				'method'      => 'POST',
				'httpversion' => '1.1',
				'blocking'    => true,
				'headers'     => $headers,
				'body'        => $data
			));
		} elseif('get' == strtolower($method)) {		
			$response = wp_remote_get($api_url, array(
				'headers'     => $headers,
				'body'        => $data,
			));
		} else {
			return null;
		}
		
		
		if(is_wp_error($response))
			return $response;
		else
//			return json_decode(wp_remote_retrieve_body($response));
			return wp_remote_retrieve_body($response);
	}
	
	/**
	 * Execute a GET API request.
	 *
	 * @param array $headers HTTP request headers.
	 * @return string The response.
 	 *
 	 * @since    1.0.0
	 */
	public function get($headers = array()) 
	{
		return $this->send('get', null, $headers);
	}
	
	/**
	 * Execute a POST API request.
	 *
	 * @param mixed $data POST request data.
	 * @param array $headers HTTP request headers.
	 * @return string The response.
 	 *
 	 * @since    1.0.0
	 */
	public function post($data, $headers = array()) 
	{
		return $this->send('post', $data, $headers);
	}
	
	/**
	 * Add query parameter for GET request's query string.
	 *
	 * @param string $key The key of the parameter.
	 * @param string $value The value of the parameter.
 	 *
 	 * @since    1.0.0
	 */
	public function addParameter($key, $value) 
	{
		$this->parameters[$key] = $value;
	}
	
	/**
	 * @return string The full URL for service call, including GET parameters.
 	 *
 	 * @since    1.0.0
	 */
	protected function getServiceURL() 
	{
		return SLIDESHARE_API_URL.$this->service.'?'.$this->getQueryString().'&'.$this->credentials->getQueryString();
	}
	
	/**
	 * Build query string for request.
	 *
	 * @return string The formatted query string.
 	 *
 	 * @since    1.0.0
	 */
	protected function getQueryString() 
	{
		return http_build_query($this->parameters);
	}
}