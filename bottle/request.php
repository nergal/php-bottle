<?php

/**
 * Объект запроса
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
        // TODO: максимально полное отражение запроса
        $this->_uri = $_SERVER['REQUEST_URI'];
    }

    /**
     * URL текущей страницы
     *
     * @return string
     */
    public function uri() {
        return $this->_uri;
    }

    /**
     * Setter для роутинга
     *
     * @param Bottle_Router $route
     * @return void
     */
    public function setRouter(Bottle_Route $route) {
        $this->route = $route;
    }

    /**
     * Getter для роутинга
     *
     * @return Bottle_Route
     */
    public function getRoute() {
        return $this->route;
    }
}
