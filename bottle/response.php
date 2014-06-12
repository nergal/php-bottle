<?php

/**
 * Response object
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
     * Response creation
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
     * Setup response body
     *
     * @param string body
     * @return void
     */
    public function setBody($body) {
        $this->_body = $body;
    }

    /**
     * Append data to body
     *
     * @param string $body
     * @return void
     */
    public function appendBody($body) {
        $this->_body.= $body;
    }

    /**
     * Returns body contents
     *
     * @return string
     */
    public function getBody() {
        return $this->_body;
    }

    /**
     * Response sending
     *
     * @return void
     */
    public function send() {
        // TODO: add caching
        // TODO: header processing
        // TODO: response code processing
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
     * Bind response wrapper
     *
     * @param Bottle_View $view
     * @return void
     */
    public function setView(Bottle_View $view) {
        $this->_view = $view;
    }

    /**
     * Getter for response wrapper
     *
     * @return Bottle_View
     */
    public function getView() {
        return $this->_view;
    }

}
