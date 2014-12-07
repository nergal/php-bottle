<?php

/**
 * Requests routing
 *
 * @package Bottle
 * @author Nergal
 */
class Bottle_Route {
    /**
     * @var string
     */
    protected $_mask;

    /**
     * @var ReflectionFunction
     */
    protected $_controller;

    /**
     * @var array
     */
    protected $_parameters = array();

    /**
     * Sets the routing mask
     *
     * @param string
     * @return void
     */
    public function setMask($route) {
        $this->_mask = $route;
    }

    /**
     * Returns the routing mask
     *
     * @return string
     */
    public function getMask() {
        return $this->_mask;
    }

    /**
     * Sets the reflection controller
     *
     * @param ReflectionFunction $instance
     * @return void
     */
    public function bindController(ReflectionFunction $instance) {
        $this->_controller = $instance;
    }

    /**
     * Returns the controller ReflectionFunction object
     *
     * @return ReflectionFunction
     */
    public function getController() {
        return $this->_controller;
    }

    /**
     * Setting query parameters
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function setParameter($name, $value) {
        $this->_parameters[$name] = $value;
    }

    /**
     * Select all query params
     *
     * @return array
     */
    public function getParameters() {
        return $this->_parameters;
    }

    /**
     * Can the current route serve the given URL?
     *
     * @param string $url
     * @return boolean
     */
    public function isServed($url) {
        $url = trim($url, '/');
        $route = trim($this->getMask(), '/');

        // TODO: add some testing
        $regex = '#^' . preg_replace('#(?:\:([a-z0-9]+))#', '(?P<$1>.+)', $route) . '$#uUi';
        if (preg_match($regex, $url, $matches)) {
            foreach ($matches as $key => $match) {
                if (!is_numeric($key)) {
                    $this->setParameter($key, $match);
                }
            }

            return TRUE;
        }
    }
}
