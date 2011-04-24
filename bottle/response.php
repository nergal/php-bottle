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
            // TODO: корректная обработка ошибок
            echo '404';
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
        echo $this->getBody();
    }
}
