Contributing to PHP-Bottle
==========================

As a free software, PHP-Bottle depends on you to be a living project in order to
help developers.

PHP-Bottle is currently a tiny baby project, and does not have a dedicated
website yet. Still, you can use this documentation site to learn how to use it
and how to make it better.


Get the Sources
---------------

The PHP-Bottle `development repository`_ and the `issue tracker`_ are both hosted
at Github. If you plan to contribute, it is a good idea to create an account
there and fork the main repository. This way your changes and ideas are visible
to other developers and can be discussed openly. Even without an account, you
can clone the repository or just download the latest development version as a
source archive.

- git: git clone git://github.com/nergal/php-bottle.git
- git/https: git clone https://github.com/nergal/php-bottle.git
- Download: `Development branch`_ as tar archive or zip file.



.. _issue tracker: https://github.com/nergal/php-bottle/issues
.. _development repository: https://github.com/nergal/php-bottle
.. _Development branch: https://github.com/nergal/php-bottle/archive/master.zip


Submitting Patches
------------------

The best way to get your changes integrated into the main development branch is
to fork the main repository at Github, create a new feature-branch, apply your
changes and send a pull-request. Further down this page is a small collection of
git workflow examples that may guide you. In any case, please follow some basic
rules:

- **Documentation**: Tell us what your patch does. Comment your code. If you
  introduced a new feature, add to the documentation so others can learn about
  it.
- **Test**: Write tests to prove that your code works as expected and does not
  break anything. If you fixed a bug, write at least one test-case that triggers
  the bug. Make sure that all tests pass before you submit a patch.
- **One patch at a time**: Only fix one bug or add one feature at a time.
  Design your patches so that they can be applyed as a whole. Keep your patches
  clean, small and focused.  Sync with upstream: If the upstream/master branch
  changed while you were working on your patch, rebase or pull to make sure that
  your patch still applies without conflicts.

Building the Documentation
--------------------------

You need a recent version of `Sphinx`_ to build the documentation. The
recommended way is to install **virtualenv** using your distribution package
repository and install sphinx manually to get an up-to-date version.

.. _Sphinx: http://sphinx-doc.org/

.. code-block:: bash

    # Install prerequisites
    which virtualenv || sudo apt-get install python-virtualenv
    virtualenv --no-site-dependencies venv
    ./venv/pip install -U sphinx sphinx-intl

    # Clone or download bottle from github
    git clone https://github.com/nergal/php-bottle.git

    # Activate build environment
    source ./venv/bin/activate

    # Build HTML docs
    cd php-bottle/docs
    make html

Now, for convenience, you can move to the *_build/html* folder, and run a
development server:

.. code-block:: bash

    python3 -m http.server

Then open a web browser to http://localhost:8000 .

This documentation is multilingual, so you can also work on translations. You
will need Poedit_ or any software that can edit .po files.

.. _Poedit: http://poedit.net/

If you want to write translations on an existing language, or even add a new
translation, you first have to build .po files using the following commands:

.. code-block:: bash

    make gettext
    sphinx-intl update -l fr -p _build/locale

Don’t forget to replace “fr” with your own language!

You now have to complete the translation process directly in Poedit. The files
to edit are in the *locale/fr/\*.po* dir (remember, use your own language code).

Poedit can generate .mo files by its own. If you can’t, for any reason, compile
your .po files into Poedit, there is a command for that:

.. code-block:: bash

    sphinx-intl build

You can compile the Sphinx documentation for your language with the following
command:

.. code-block:: bash

    make SPHINXOPTS="-Dlanguage=fr" html

**If** you added a new language, you will have to open an issue on Github,
asking for creation of that lang. In the meantime, you are free to submit your
translations with pull-requests.
