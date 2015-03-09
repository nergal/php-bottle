Quickstart
==========

This short guide will show you how to start a PHP-Bottle project. It should take
only a few minutes to make you understand the basics.

Installation
------------

You first need a working PHP5 environment. An HTTP server is not required for
development.

#. Create a directory for your application (let’s call it *projectDir*)
#. Fetch the last PHP-Bottle archive here:
   https://github.com/nergal/php-bottle/raw/master/build/bottle.phar and put it
   in the root of *projectDir*
#. Fetch the *.htaccess* file that will enable URL rewriting here:
   https://github.com/nergal/php-bottle/raw/master/.htaccess and put it near the
   *bottle.phar* file.
#. Create a *views* directory in *projectDir*

You’re done! Everything is ready to write your first PHP-Bottle page!

First page
----------

Okay, last step was too complicated, I got that. So let’s make things simpler:
just copy that in a *index.php* file:

.. code-block:: php

    <?php

    define('APPLICATION_PATH', realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR);

    require_once('bottle.phar');

    /**
     * @route /
     */
    function index() {
        return 'Welcome on the Bottle index page!';
    }

Well, was that too hard? We’re ready to test that. Open a terminal, move to
*projectDir*, and type:

    $ php -S localhost:8000

**Don’t close the terminal yet!** We’ve just started a development HTTP server.
Yup, it was embedded in PHP all that time (well, since `PHP 5.4.0`__). Now, just
open a web browser to the following URL: http://localhost:8000 . You should see
a page welcoming you. Great, that’s a basic hello world, and we didn’t have to
write a frawemork to do that!

__ http://php.net/manual/en/features.commandline.webserver.php

But let’s make something cooler. Like **dynamic URLs**!

Dynamic URLs
------------

Did you notice the funny comment just before the *index* function? That’s a
**decorator**, but some weird frameworks call that an **annotation**. We’ll keep
the first one.

That decorator tells PHP-Bottle when to call that function: in this case, when
an URL matching “/” is found. Try this: change that line, replacing “/” by
“my/dynamic/url”. Refresh the page (no need to restart the development server we
started before), and don’t fear: you just met a 404 error.

Why? Because no function (we call these *controllers*) has been registered for
the “/” URL. In other words, the requested page is not found.

But what if you try to get to http://localhost:8000/my/dynamic/url ? Right, your
welcome message is here. We changed the *route* decorator, and it literally
changed the URL of our page.

For the next step, we should go back to an index page routed on the */* URL. So
go back to the “First page” example.

Multiple pages
--------------

Add this to the end of your *index.php* file:

.. code-block:: php

    /**
     * @route /hello/:name
     */
    function hello($name) {
        return 'Hello {$name}!';
    }

Look closely at the defined *route* for this controller: it contains a word
prefixed by a colon. That means the “:name” is a variable, a dynamic part of a
(already dynamic) URL. You can call whatever URL beginning by “/hello/”, and the
rest will be in the $name variable. Try it, with
http://localhost:8000/hello/world , then replace “world” by your own name.

You can see that the “:name” part of the URL is indeed given to the *hello()*
function as the *$name* argument.

A route can define as many variables as you wish, if you add them as params for
your controller.

Views
-----

Remember the :ref:`main page <index>`, telling that PHP-Bottle was a (M)VC framework? Well,
we’ve seen the C(ontroller), let’s see the V(iew).

A view is the “how do I show it” part of an application, opposing to a
controller, which is the “what do I show”.

So basically, a view is a code meant only to display something.

PHP-Bottle has chosen to use a well-known template engine to do that: PHP.

In order to use views, you first have to declare one in your decorators. Here is
an example:

.. code-block:: php

    /**
     * @route /view-test
     * @view /views/view-test.php
     */
    function view_test() {
        return ['var' => 'world'];
    }

The @view decorator links to the position of the view file, relative to the
index.php file. You can use whatever extension you like for the view.

The second step, after the @view decorator, is to return an associative array in
your controller. Keys of this array will be extracted into vars available in
your view. So, in */views/view-test.php*, you can display the way you want the
*$var* variable, as defined in the controller. Write in */views/view-test.php*:

.. code-block:: php+html

    <h1>Hello <?= $var ?></h1>

Now, open your browser to http://localhost:8000/view-test and observe. You see a
<h1> tag, saying “Hello world”. The last part of this sentence was given by the
controller. It could as well be a dynamic value.
