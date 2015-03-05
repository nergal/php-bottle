<?php

define('APPLICATION_PATH', realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR);

require_once('build/bottle.phar');

function authenticated($request) {
    return $request->getParam('password') == 'bottleisacoolframework';
}

/**
 * @route /
 */
function index() {
    return 'Welcome on the Bottle index page!';
}

/**
 * @route /hello/:name
 */
function hello($name) {
    return "<h1>Hello, {$name}!</h1>";
}

/**
 * @route /mul/:num
 * @view /views/mul.php
 */
function mul($num) {
    return ['result' => $num * $num];
}

/**
 * @route /restricted
 * @requires authenticated
 * @view /views/restricted.php
 */
function restricted() {
    return ['status' => 'OK'];
}
