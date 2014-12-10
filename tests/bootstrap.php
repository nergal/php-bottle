<?php

if(!defined('APPLICATION_PATH')) {
    define('APPLICATION_PATH', realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
}

// require_once(APPLICATION_PATH . '../build/bottle.phar');

require_once(APPLICATION_PATH . 'utils.php');
require_once(APPLICATION_PATH . 'classes/bottleTestCase.php');

require_once(APPLICATION_PATH . '../src/bottle/request.php');
class testRequest extends Bottle_Request
{
    public function __construct() { }

    public function getDocroot()
    {
        return '/';
    }
}

$request = new testRequest();