<?php

define('APPLICATION_PATH', realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR);

/**
 * Framework initialization
 *
 * @package bottle
 * @author nergal
 */
class Bottle {
    /**
     * Autoload classes
     *
     * @param string $classname
     * @return boolean
     */
    public function autoload($classname) {
        $classname = strtolower($classname);
        $filename = APPLICATION_PATH . str_replace('_', DIRECTORY_SEPARATOR, $classname) . '.php';

        if (file_exists($filename)) {
            require_once $filename;
        }

        return class_exists($classname);
    }

    /**
     * Exception handler
     *
     * @param Exception $e
     * @return void
     */
    public function exceptionHandler($e) {
        $html = '<style type="text/css">
            .bottle-framework-exception {background-color: #f0f5ff;padding: 5px;border: 1px solid #d5e5ee;font-size:12px;font-family: Tahoma, Arial;}
            .bottle-framework-exception h1 {background-color: #cce;margin: 0px;padding: 5px;font-size:13px;}
            .bottle-framework-exception h1 span {color: #555;}
            .bottle-framework-exception h1.right {float:right;margin-right: 5px;font-weight:normal}
            .bottle-framework-trace {list-style-type: none;padding: 0px;border: 1px solid #cce;border-bottom: 0px;}
            .bottle-framework-trace li {border-bottom: 1px solid #cce;background-color: #fff;padding: 3px 2px;}</style>';

        $type = 'Unknown exception';
        switch ($e->getCode()) {
            case E_USER_ERROR:
            case E_ERROR:
                $type = 'Error';
                break;
            case E_USER_WARNING:
            case E_WARNING:
                $type = 'Warning';
                break;
            case E_USER_NOTICE:
            case E_NOTICE:
                $type = 'Notice';
                break;
        }

        $html.= '<div class="bottle-framework-exception">';
        $html.= '<h1 class="right">in file ' . $e->getFile() . ':' . $e->getLine() . '</h1>';
        $html.= '<h1><span>' . $type . ':</span> ' . $e->getMessage() . '</h1>';
        $html.= '<ol class="bottle-framework-trace">';

        $trace = $e->getTrace();
        foreach ($trace as $key => $iteration) {
            $file = (isset($iteration['line']) ? ($iteration['file'] . ':' . $iteration['line']) : '');
            $function = (isset($iteration['class']) ? $iteration['class'] : '');
            $function.= (isset($iteration['type']) ? $iteration['type'] : '');
            $function.= $iteration['function'] . '()';
            $html.= '<li>#' . $key . ' ' . $function . ' in ' . $file . '</li>';
        }
        $html.= '</ol></div>';

        echo $html;
    }

    /**
     * Errors handler
     *
     * @param integer $errno
     * @param string $errstr
     * @param string $errfile
     * @param string $errline
     * @return void
     */
    public function errorHandler($errno, $errstr, $errfile, $errline) {
        $exception = new ErrorException($errstr, 0, $errno, $errfile, $errline);
        return $this->exceptionHandler($exception);
    }

    /**
     * Fatal error handler
     *
     * @todo некорректно работает - отстреливаются пред. обработчики
     * @return void
     */
    public function shutdownHandler() {
        $last_error = error_get_last();
        if ($last_error['type'] === E_ERROR OR $last_error['type'] === E_USER_ERROR) {
            ob_clean();
            $this->errorHandler(
                $last_error['type'],
                $last_error['message'],
                $last_error['file'],
                $last_error['line']
            );
        }
    }

    /**
     * Handlers binding
     *
     * @construct
     * @return void
     */
    public function __construct() {
        set_exception_handler(array($this, 'exceptionHandler'));
        set_error_handler(array($this, 'errorHandler'));

        register_shutdown_function(array($this, 'shutdownHandler'));
        spl_autoload_register(array($this, 'autoload'), TRUE, TRUE);

        Bottle_Core::start();
    }
}

new Bottle;
