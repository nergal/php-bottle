<?php

/**
 * Объект ответа
 *
 * @package Bottle
 * @author Nergal
 */
class Bottle_Response {
    /**
     * @var string
     */
    protected $_body = '';

    /**
     * @var Bottle_View
     */
    protected $_view = NULL;

    /**
     * Генерация ответа
     *
     * @param Bottle_Request $request
     */
    public function dispatch(Bottle_Request $request) {
        if ($request->route !== NULL) {
            $controller = $request->route->getController();
            $parameters = $request->route->getParameters();

            $body = $controller->invokeArgs($parameters);
            $this->setBody($body);
        } else {
            throw new Bottle_Exception('No route found');
        }

        $this->send();
    }

    /**
     * Устанавливает содержимое ответа
     *
     * @param string body
     * @return void
     */
    public function setBody($body) {
        $this->_body = $body;
    }

    /**
     * Добавляет данные к ответу
     *
     * @param string $body
     * @return void
     */
    public function appendBody($body) {
        $this->_body.= $body;
    }

    /**
     * Возвращает содержимое ответа
     *
     * @return string
     */
    public function getBody() {
        return $this->_body;
    }

    /**
     * Отправка запроса клиенту
     *
     * @return void
     */
    public function send() {
        // TODO: кэширование
        // TODO: обработка заголовков
        // TODO: обработка кода ответа
        $body = $this->getBody();
        $view = $this->getView();

        if (is_array($body) AND $view !== NULL) {
            $view->bind($body);
            echo $view->render(TRUE);
        } else {
            echo $body;
        }
    }

    /**
     * Назначение враппера ответа
     *
     * @param Bottle_View $view
     * @return void
     */
    public function setView(Bottle_View $view) {
        $this->_view = $view;
    }

    /**
     * Getter для враппера ответа
     *
     * @return Bottle_View
     */
    public function getView() {
        return $this->_view;
    }

}
