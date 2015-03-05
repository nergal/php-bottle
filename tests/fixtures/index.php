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

/**
 * Conditions for the following controllers
 */
function true_condition($request) {
    return true;
}

function false_condition($request) {
    return false;
}

function arg_condition($request, $arg) {
    return $arg[0] == 'arg_0';
}

function dyn_arg_condition($request, $arg) {
    return $arg[0] == 'url_param';
}

/**
 * @route /restricted
 * @requires true_condition
 */
function restricted() {
    return 'OK';
}

/**
 * @route /restricted2
 * @requires false_condition
 */
function restricted2() {
    return 'OK';
}

/**
 * @route /restricted3
 * @requires arg_condition arg_0
 */
function restricted3() {
    return 'OK';
}

/**
 * @route /restricted4
 * @requires arg_condition nope
 */
function restricted4() {
    return 'OK';
}

/**
 * @route /restricted5/:value
 * @requires dyn_arg_condition $value
 */
function restricted5($value) {
    return 'OK';
}

function global_context($request) {
    return ['var1' => 'global', 'var2' => 'global2'];
}

/**
 * @route /global-context
 * @view /views/global-context.html
 */
function global_context_controller() {
    return ['var2' => 'local', 'var3' => 'local2'];
}
