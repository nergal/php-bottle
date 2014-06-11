<?php

/**
 * View wrapper
 *
 * @package Bottle
 * @author Nergal
 */
class Bottle_View {
    /**
     * @var string
     */
    protected $_filename = NULL;

    /**
     * @var array
     */
    protected $_params = array();

    /**
     * Creating a view
     *
     * @param string $filename
     * @return self
     */
    public function __construct($filename = NULL) {
        if ($filename !== NULL) {
            $this->setFilename($filename);
        }
    }

    /**
     * Setting the file name for the wrapper
     *
     * @param string $filename
     * @return void
     */
    public function setFilename($filename) {
        $realname = realpath(APPLICATION_PATH . ltrim($filename, DIRECTORY_SEPARATOR));

        if (!file_exists($realname)) {
            throw new Bottle_Exception('No such file ' . $filename);
        }

        $this->_filename = $realname;
    }

    /**
     * Sets the view variables returned by the controller
     *
     * @param array $params
     * @return void
     */
    public function bind(Array $params) {
        $this->_params = $params;
    }

    /**
     * Rendering the view
     *
     * @param boolean $return_output
     * @return string|void
     */
    public function render($return_output = FALSE) {
        ob_start();

        extract($this->_params, EXTR_OVERWRITE);
        include $this->_filename;

        $output = ob_get_clean();
        if ($return_output === TRUE) {
            return $output;
        }

        echo $output;
    }
}
