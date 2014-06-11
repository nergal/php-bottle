<?php

/**
 * Response object
 *
 * @package Bottle
 * @author Nergal
 */
class Bottle_Response {
    /**
     * @var string|array
     */
    protected $_body = '';

    /**
     * @var Bottle_View
     */
    protected $_view = NULL;

    /**
     * Response generation
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
     * Sets the response content
     *
     * @param string|array body
     * @return void
     */
    public function setBody($body) {
        $this->_body = $body;
    }

    /**
     * Adds data to content
     *
     * @param string $body
     * @return void
     */
    public function appendBody($body) {
        // todo: what if body is an array?
        $this->_body.= $body;
    }

    /**
     * Returns the response's content
     *
     * @return string|array
     */
    public function getBody() {
        return $this->_body;
    }

    /**
     * Sending the request to the client
     *
     * @return void
     */
    public function send() {
        // TODO: Caching
        // TODO: Header processing
        // TODO: Response code treatment
        $body = $this->getBody();
        $view = $this->getView();

        // $body can be an array if the @view annotation is used

        if (is_array($body) AND $view !== NULL) {
            $view->bind($body);
            echo $view->render(TRUE);
        } else {
            echo $body;
        }
    }

    /**
     * Response view wrapper assignment
     *
     * @param Bottle_View $view
     * @return void
     */
    public function setView(Bottle_View $view) {
        $this->_view = $view;
    }

    /**
     * Getter for the response view wrapper
     *
     * @return Bottle_View
     */
    public function getView() {
        return $this->_view;
    }

}
