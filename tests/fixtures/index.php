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

require '../../src/bottle.php';

/**
 * @route /
 */
function index() {
    return 'Success';
}

/**
 * @route /param/:name
 */
function param($name) {
    return 'Param: '.$name;
}

/**
 * @route /view/:name
 * @view /views/view.html
 */
function view($name) {
    return ['name' => $name];
}

/**
 * @route /exception
 */
function exception() {
    throw new Exception('You shouldn’t be here.');
}
