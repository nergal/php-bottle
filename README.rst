PHP Bottle Web Framework
========================
PHP Bottle - PHP microframework inspired by minimalistic PyBottle

Install
------
Copy the bottle.phar and .htaccess in your working directory.

It’s done! You now just have to write pages, MVC style!

Example
-------

Put this into your index.php file. Each function containing a “route” annotation
is a page, served by a specific URL. Feel free to use `require` statements to
organize your code as you wish. The final line will start Bottle.

::

    <?php

    /**
     * @route /hello/:name
     */
    function hello($name) {
        return "<h1>Hello, {$name}!</h1>";
    }

    /**
     * @route /mul/:num
     * @view /views/mul.html
     */
    function mul($num) {
        return ['result' => $num * $num];
    }

    require_once('bottle.phar');

As you see, there is a reference to a “/views/mul.html” file. This one must be
created in a “views” directory right under your index.php file. Here is the
matching view:

::

    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8" />
            <title>Bottle mul page</title>
        </head>
        <body>
            <h1>Here is the result: <?= $result ?>
        </body>
    </html>

Now, points a web browser to your directory, and browse to http://localhost/[your_directory_path]/hello/NAME
and replace `NAME` by your own name. Then, browse to
http://localhost/[your_directory_path]/mul/NUM , where `NUM` is a number. It
works!

But your local installation may have subdirectories, and your web application is
intended to be directly on the docroot. No problem, let’s use the embedded PHP
development server!

In a terminal, jump to your project’s directory, and run:

::

    php -S localhost:8000

Now, just go to http://localhost:8000 . Your Bottle routes will take place at
the docroot.

Licence (MIT)
-------------

    Copyright (c) 2010, Marcel Hellkamp.

    Permission is hereby granted, free of charge, to any person obtaining a copy
    of this software and associated documentation files (the "Software"), to deal
    in the Software without restriction, including without limitation the rights
    to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
    copies of the Software, and to permit persons to whom the Software is
    furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in
    all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
    OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
    THE SOFTWARE.}
