<?php

define('APPLICATION_PATH', realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR);

/**
 * Автозагрузка классов
 *
 * @param string $classname
 * @reurn boolean
 */
function __autoload($classname) {
    $classname = strtolower($classname);
    $filename = APPLICATION_PATH . str_replace('_', DIRECTORY_SEPARATOR, $classname) . '.php';

    if (file_exists($filename)) {
        require_once $filename;
    }

    return class_exists($classname);
}

// Вызов фреймворка
Bottle_Core::start();