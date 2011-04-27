<?php

/**
 * Враппер овета
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
     * Создание враппера
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
     * Установка имени файла для враппера
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
     * Назначение переменных окружения
     *
     * @param array $params
     * @return void
     */
    public function bind(Array $params) {
        $this->_params = $params;
    }

    /**
     * Рендеринг результата
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
