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

require '../../bottle.php';

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
