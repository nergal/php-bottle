<?php

/**
 * Request object
 *
 * @package Bottle
 * @author Nergal
 */
class Bottle_Request {

    /**
     * @var string the optional parent dir (allows bottle to be installed in a 
     * subdirectory
     */
    protected $_docroot = '';

    /**
     * @var string
     */
    protected $_uri = '';

    /**
     * @var Bottle_Route
     */
    public $route = NULL;

    /**
     * @var array
     */
    protected $_params = array();

    /**
     * @var array
     */
    public $headers = array();

    /**
     * The class constructor
     *
     * @constructor
     * @return self
     */
    public function __construct() {
        // @TODO most accurate request reflection
        // truncating the document root
        $docroot = rtrim(dirname(substr($_SERVER['SCRIPT_FILENAME'],
                                        mb_strlen(rtrim($_SERVER['DOCUMENT_ROOT'],
                                                        '/'),
                                                  'utf-8'
                                                 )
                                       )
                                ),
                         '/').'/';
        $this->_docroot = $docroot;
        // truncating GET params
        $uri = substr($_SERVER['REQUEST_URI'], strlen($docroot)-1);
        if(strpos($uri, '?') != false) {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }
        $this->_uri = $uri;
        $this->_params = $_REQUEST;

        // getting request headers
        $this->headers = $this->parseHeaders($_SERVER);
    }

    /**
     * Current URL
     *
     * @return string
     */
    public function uri() {
        return $this->_uri;
    }

    /**
     * Setter for routing
     *
     * @param Bottle_Router $route
     * @return void
     */
    public function setRouter(Bottle_Route $route) {
        $this->route = $route;
    }

    /**
     * Getter for routing
     *
     * @return Bottle_Route
     */
    public function getRoute() {
        return $this->route;
    }

    /**
     * Getter for all GET/POST params
     *
     * @return array
     */
    public function getParams() {
        return $this->_params;
    }

    /**
     * Getter for one param, optionnally returning a given default value
     *
     * @param string $name
     * @param string $default optional
     * @return string|false false is returned if the param does not exists and 
     * no default value is given
     */
    public function getParam($name, $default = false) {
        if(isset($this->_params[$name])) {
            return $this->_params[$name];
        } else {
            return $default;
        }
    }

    /**
     * Request headers parser
     * Returns an associative array of the headers found in $_SERVER, in 
     * (normally) correct case.
     *
     * The original function is found here: 
     * http://us2.php.net/manual/fr/function.apache-request-headers.php#70810
     *
     * @param array $server
     * @return array
     */
    protected function parseHeaders($server) {
        $arh = array();
        $rx_http = '/\AHTTP_/';
        foreach($server as $key => $val) {
            if( preg_match($rx_http, $key) ) {
                $arh_key = preg_replace($rx_http, '', $key);
                $rx_matches = array();
                // do some nasty string manipulations to restore the original letter case
                // this should work in most cases
                $rx_matches = explode('_', $arh_key);
                if( count($rx_matches) > 0 and strlen($arh_key) > 2 ) {
                    foreach($rx_matches as $ak_key => $ak_val) {
                        $rx_matches[$ak_key] = ucfirst(strtolower($ak_val));
                    }
                    $arh_key = implode('-', $rx_matches);

                    // exception for the DNT header, which must stay uppercase
                    if($arh_key == 'Dnt') {
                        $arh_key = strtoupper($arh_key);
                    }

                }

                $arh[$arh_key] = $val;
            }
        }
        return( $arh );
    }

    /**
     * getter for all headers
     *
     * @return array
     */
    public function getHeaders(){
        return $this->headers;
    }


    /**
     * getter for a given header
     *
     * @param string $header_name
     * @return string|null
     */
    public function getHeader($header_name) {
        if(isset($this->headers[$header_name])) {
            return $this->headers[$header_name];
        } else {
            return null;
        }
    }

    /**
     * helper method to know if we’re on an AJAX request
     *
     * @return bool
     */
    public function isAjax(){
        return $this->getHeader('X-Requested-With') == 'XMLHttpRequest';
    }
}
