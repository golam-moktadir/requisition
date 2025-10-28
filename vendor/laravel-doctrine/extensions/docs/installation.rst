============
Installation
============

Install this package with composer:

.. code-block:: bash

  composer require laravel-doctrine/extensions

This package wraps extensions from `Gedmo <https://github.com/Atlantic18/DoctrineExtensions>`_
and `Beberlei <https://github.com/beberlei/DoctrineExtensions>`_.


To include Gedmo extensions install them with composer:

.. code-block:: bash

  composer require "gedmo/doctrine-extensions"


Enable the service provider for them in Laravel:

.. code-block:: php

  LaravelDoctrine\Extensions\GedmoExtensionsServiceProvider::class

Also, be sure to enable the extensions in the ``extensions`` section of
``config/doctrine.php``.


To include Beberlei (Query/Type) extensions install them:

.. code-block:: bash

  composer require "beberlei/DoctrineExtensions=^1.0"


And then add the Beberlei extensions service provider in `config/app.php`:

.. code-block:: php

  LaravelDoctrine\Extensions\BeberleiExtensionsServiceProvider::class


.. role:: raw-html(raw)
   :format: html

.. include:: footer.rst
