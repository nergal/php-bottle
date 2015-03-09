.. PHP-Bottle documentation master file, created by
   sphinx-quickstart on Sun Mar  8 22:00:27 2015.
   You can adapt this file completely to your liking, but it should at least
   contain the root `toctree` directive.

.. _index:

Welcome to PHP-Bottle's documentation!
======================================

PHP Bottle - PHP micro-framework inspired by minimalistic PyBottle_.

.. _PyBottle: http://bottle.py.org

Its goal is to provide a lightweight stack to quickly build modern web
applications in PHP. It provides (M)VC approach, dynamic routing, support for
any kind of templating, and it’s very agnostic.

Here is an example of what you’ll have to write in order to get a dynamic web
application with PHP-Bottle:

.. code-block:: php

    <?php

    define('APPLICATION_PATH', realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR);

    require_once('bottle.phar');

    /**
     * This is a condition function for a route.
     */
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

Contents:

.. toctree::
   :maxdepth: 2

   quickstart
   installation
   routing
   reference/request
   reference/response
   reference/templating
