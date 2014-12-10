<?php
/**
 * Fixtures app for PHP-Bottle tests
 *
 * @package php-bottle
 * @author Damien Nicolas <damien@gordon.re>
 * @version 0.1
 * @license MIT
 */

define('APPLICATION_PATH', realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR);

// require '../../../src/bottle.php';
require '../../../build/bottle.phar';

/**
 * @route /
 */
function index() {
    return 'Success';
}

/**
 * @route /é
 */
function index2() {
    return 'Successé';
}

/**
 * @route /'
 */
function index3() {
    return 'Success\'';
}

/**
 * @route / 
 */
function index4() {
    return 'Success ';
}
