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
        $this->_uri = $_SERVER['REQUEST_URI'];
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
