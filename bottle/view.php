<?php

/**
 * Response wrapper
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
     * Wrapper creation
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
     * Set filename for wrapper
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
     * Env variables binding
     *
     * @param array $params
     * @return void
     */
    public function bind(Array $params) {
        $this->_params = $params;
    }

    /**
     * Results rendering
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
