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
     * @var array
     */
    protected $_headers = array();

    /**
     * @var Bottle_View
     */
    protected $_view = NULL;

    /**
     * @var bool
     */
    protected $_sendBody = true;

    /**
     * Response generation
     *
     * @param Bottle_Request $request
     */
    public function dispatch(Bottle_Request $request) {
        if ($request->route !== NULL) {
            $controller = $request->route->getController();
            $parameters = $request->route->getParameters();
            $condition = $request->route->getCondition();
            if($condition) {
                // calling the condition function
                $result = $condition[0]($request, $condition[1]);
                if(!$result) {
                    throw new Bottle_Forbidden_Exception('Forbidden');
                }
            }

            $body = $controller->invokeArgs($parameters);

            // if the controller returns an array, we try to call a
            // "global_context" function, which will add context to $body
            if(is_array($body)) {
                if(function_exists('global_context')) {
                    $context = global_context($request);
                    if(!is_array($context)) {
                        throw new Bottle_Exception('global_context function must return an array');
                    }
                    $body = array_merge($context, $body);
                }
            }

            $this->setBody($body);
        } else {
            throw new Bottle_NotFound_Exception('No route found');
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
        foreach($this->_headers as $header_name => $header_value) {
            header($header_name.': '.$header_value);
        }
        if(!$this->_sendBody) {
            return;
        }
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

    /**
     * Getter for all response headers
     *
     * @return array
     */
    public function getHeaders() {
        return $this->_headers;
    }

    /**
     * Getter for a single response header, returns false if it does not exist.
     *
     * @param string $header
     *
     * @return string|false
     */
    public function getHeader($header) {
        if(isset($this->_headers[$header])) {
            return $this->_headers[$header];
        } else {
            return false;
        }
    }

    /**
     * Setter for a response header.
     *
     * @param string $header the name of the header
     * @param string $value
     *
     */
    public function setHeader($header, $value) {
        $this->_headers[$header] = $value;
    }

    /**
     * delete a response header
     *
     * @param string $header
     */
    public function deleteHeader($header) {
        if(isset($this->_headers[$header])) {
            unset($this->_headers[$header]);
        }
    }

    /**
     * sends a Location header, redirecting to a given URL or route.
     *
     * @param array|string $location
     * @return null
     */
    public function redirect($location) {
        if(is_array($location)) {
            $url = $this->getView()->url($location[0], $location[1]);
        } elseif($location[0] != '/') {
            $url = $this->getView()->url($location);
        } else {
            $url = $location;
        }
        $this->setHeader('Location', $url);
        $this->_sendBody = false;
        return null;
    }

}
