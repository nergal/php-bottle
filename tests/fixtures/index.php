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

// require '../../src/bottle.php';
require '../../build/bottle.phar';

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

/**
 * @route /redirect
 * @view /views/view.html
 */
function redirect() {
    global $response;
    $response->redirect('redirected_function');
}

/**
 * @route /redirected
 */
function redirected_function() {
    return 'Redirected';
}

/**
 * @route /header
 */
function header_route() {
    global $response;
    $response->setHeader('Content-type', 'text/plain');
    return 'Header';
}

/**
 * @route /url
 * @view /views/view.html
 */
function url() {
    global $response;
    return $response->getView()->url('redirected_function');
}

/**
 * @route /url2
 * @view /views/view.html
 */
function url2() {
    global $response;
    return $response->getView()->url('param', ['name' => 'name']);
}
