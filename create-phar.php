<?php
/**
 * Short description for create-phar.php
 *
 * @package php-bottle
 * @author Damien Nicolas <damien@gordon.re>
 * @version 0.1
 * @license MIT
 */

if(ini_get('phar.readonly') == 'On') {
    exit('Your system cannot create PHAR archives. Exiting...'.PHP_EOL);
}

$srcRoot = realpath(dirname(__FILE__));
$buildRoot = realpath(dirname(__FILE__).'/build');

$phar = new Phar($buildRoot.'/bottle.phar', 0,  'bottle.phar');
$build = $phar->buildFromDirectory($srcRoot.'/src', '/\.php$/');
var_dump($build);
$phar->setStub($phar->createDefaultStub('bottle.php'));
