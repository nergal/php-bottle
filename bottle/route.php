<?php

/**
 * Объект роутера запросов
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
     * @var ReflectionFunctionAbstract
     */
    protected $_controller;

    /**
     * @var array
     */
    protected $_parameters = array();

    /**
     * Устанавливает маску роутинга
     *
     * @param string
     * @return void
     */
    public function setMask($route) {
        $this->_mask = $route;
    }

    /**
     * Возвращает строку роутинга
     *
     * @return string
     */
    public function getMask() {
        return $this->_mask;
    }

    /**
     * Устанавливает отражение контроллера
     *
     * @param ReflectionFunctionAbstract $instance
     * @return void
     */
    public function bindController(ReflectionFunctionAbstract $instance) {
        $this->_controller = $instance;
    }

    /**
     * Забирает отражение контроллера
     *
     * @return ReflectionFunctionAbstract
     */
    public function getController() {
        return $this->_controller;
    }

    /**
     * Установка параметра запроса
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function setParameter($name, $value) {
        $this->_parameters[$name] = $value;
    }

    /**
     * Выборка всех параметров запроса
     *
     * @return array
     */
    public function getParameters() {
        return $this->_parameters;
    }

    /**
     * Обслуживает ли данный роут текущий запрос
     *
     * @param string $url
     * @return boolean
     */
    public function isServed($url) {
        $url = trim($url, '/');
        $route = trim($this->getMask(), '/');

        // TODO: добавить тесты
        $regex = '#^' . preg_replace('#(?:\:([_a-z0-9]+))#', '(?P<$1>.+)', $route) . '$#uUi';
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
