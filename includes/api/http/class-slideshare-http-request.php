<?php
/**
 * Request for REST API calls.
 *
 * @package   SlideshareHttpRequest
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-api-import
 * @see       http://fr.slideshare.net/developers/documentation
 * @copyright 2014 Spoon
 *
 * @since    1.0.0
 */
class SlideshareHttpRequest 
{	
	private $credentials;
	private $service;
	private $parameters = array();
	
	/**
	 * Constructor
	 *
	 * @param string $service The service REST parameter to call
	 * @param SlideshareCredentials $credentials Credential object for API validation
 	 *
 	 * @since    1.0.0
	 */
	public function __construct($service, SlideshareCredentials $credentials) 
	{
		$this->service = $service;
		$this->credentials = $credentials;
	}
	
	/**
	 * Execute a Slideshare API call from service URL.
	 *
	 * @param string $method The request method GET|POST.
	 * @param mixed $data Optional request parameters (used for POST method only).
	 * @param array $headers HTTP request headers.
	 *
	 * @throws SlideshareHttpException
 	 *
 	 * @since    1.0.0
	 */
	public function send($method, $data = null, $headers = array()) 
	{
		try {
			add_filter('https_ssl_verify', '__return_false');

            $method = strtolower($method);
			if(in_array($method, array('get', 'post'))) {
				$http = array(
					'method'      => strtoupper($method),
					'headers'     => $headers,
				);
                
                if('post' == $method) {
                    $http['content'] = $data;
                }
			} else {
				throw new SlideshareHttpException(__('HTTP error'), __("The HTTP method '$method' is not allowed"));
			}
		
			$context = stream_context_create(array('http' => $http));
			$response = file_get_contents($this->getServiceURL(), false, $context);
			
			if(is_wp_error($response)) {
				throw new SlideshareHttpException($response->get_error_code(), $response->get_error_message());
			}
			
			return new SlideshareHttpResponse($response);
		} catch (Exception $exception) {
			return new SlideshareHttpResponse($exception);
		}
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
		return SLIDESHARE_API_URL.$this->service.'?'.$this->getQueryString();
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
		return http_build_query($this->parameters).'&'.$this->credentials->getQueryString();
	}
}