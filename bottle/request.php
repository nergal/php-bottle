<?php

/**
 * Request object
 *
 * @package Bottle
 * @author Nergal
 */
class Bottle_Request {
    /**
     * @var string
     */
    protected $_uri = '';

    /**
     * @var Bottle_Route
     */
    public $route = NULL;

    /**
     * Конструктор класса
     *
     * @constructor
     * @return self
     */
    public function __construct() {
        // @TODO most accurate request reflection
        // truncating the document root
        $docroot = dirname(substr($_SERVER['SCRIPT_FILENAME'],
                           mb_strlen(rtrim($_SERVER['DOCUMENT_ROOT'],
                                           '/'),
                                     'utf-8')));
        // fixing the dirname() behaviour on windows: we have to delete any \
        $docroot = str_replace('\\', '', $docroot);
        $this->_docroot = $docroot;
        // truncating GET params
        $uri = substr($_SERVER['REQUEST_URI'], strlen($docroot)-1);
        if(strpos($uri, '?') != -1) {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }
        $this->_uri = $uri;
        $this->_params = $_REQUEST;
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
}
