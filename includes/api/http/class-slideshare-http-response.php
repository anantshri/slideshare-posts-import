<?php
/**
 * Response for REST API calls.
 *
 * @package   SlideshareHttpResponse
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-api-import
 * @see       http://fr.slideshare.net/developers/documentation
 * @copyright 2014 Spoon
 *
 * @since    1.0.0
 */
class SlideshareHttpResponse
{	
	private $data;
	
	/**
	 * Constructor
	 *
	 * @param mixed $data The service response data
 	 *
 	 * @since    1.0.0
	 */
	public function __construct($data) 
	{
		$this->data = $data;
	}
	
	/**
	 * Parse XML response from Slideshare API
	 *
	 * @return SlideshareModel|false
	 *
	 * @throws SlideshareHttpException
	 * @throws SlideshareParserException
	 * @throws SlideshareException
	 * @throws SlideshareServiceException
 	 *
 	 * @since    1.0.0
	 */
	public function parse($object = null)
	{
		if(!$this->data) {
			throw new SlideshareHttpException(__("HTTP error"), __("Slideshare HTTP error on received data"));
		} else {
			if($this->data instanceof Exception) {
				throw $this->data;
			} else {
                if($this->isHTML($this->data)) {
                    throw new SlideshareException(SlideshareException::SLIDESHARE_ERROR_PAGE, utf8_encode($this->data));
                }
				$parser = new SlideshareXMLParser(utf8_encode($this->data));
				
				try {
					$result = $parser->parse($object);
					
					if($result instanceof SlideshareModel) {
						return $result;
					}
				} catch(SlideshareParserException $exception) {
					throw $exception;
				} catch(SlideshareServiceException $exception) {
					throw $exception;
				} catch(SlideshareException $exception) {
					throw $exception;
				}
			}
		}
	}
	
	/**
	 * Get response data
	 *
	 * @return string The service response data
 	 *
 	 * @since    1.0.0
	 */
	public function getData()
	{
		return $this->data;
	}
	
	/**
	 * Checks if data is HTML content
	 *
     * @param string $str The string to check
     * @param boolean $count 
	 * @return boolean
     * @link http://phpsnips.com/65/Check-a-string-for-HTML#.U6AJ1ZTV_TU
 	 *
 	 * @since    1.0.0
	 */
    private function isHTML($str, $count = false)
    {
        $html = array(
            'html'
            // 'a','abbr','acronym','address','applet','area',
            // 'b','base','basefont','bdo','big','blockquote','body','br','button',
            // 'caption','center','cite','code','col','colgroup',
            // 'dd','del','dfn','dir','div','dl','dt',
            // 'em',
            // 'fieldset','font','form','frame','frameset',
            // 'h1','h2','h3','h4','h5','h6','head','hr','html',
            // 'i','iframe','img','input','ins','isindex',
            // 'kbd',
            // 'label','legend','li','link',
            // 'map','menu','meta',
            // 'noframes','noscript',
            // 'object','ol','optgroup','option',
            // 'p','param','pre',
            // 'q',
            // 's','samp','script','select','small','span','strike','strong','style','sub','sup',
            // 'table','tbody','td','textarea','tfoot','th','thead','title','tr','tt',
            // 'u','ul',
            // 'var'
        ); 
        
        if(preg_match_all("~(<\/?)\b(".implode('|', $html).")\b([^>]*>)~i", $this->data, $c)){ 
            if($count) {
                return array(true, count($c[0])); 
            }
            return true;
        }
        return false; 
    }
}