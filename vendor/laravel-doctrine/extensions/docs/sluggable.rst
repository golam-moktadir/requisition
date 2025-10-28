=========
Sluggable
=========

Sluggable behavior will build the slug of predefined fields on a given field
which should store the slug

* Automatic predefined field transformation into slug.
* Slugs can be unique and styled, even with prefixes and/or suffixes.
* Can be nested with other behaviors.
* Annotation, Yaml and Xml mapping support for extensions.
* Multiple slugs, different slugs can link to same fields.


Installation
============

Add ``LaravelDoctrine\Extensions\Sluggable\SluggableExtension``
to ``doctrine.extensions`` config in ``config/doctrine.php``.


Further Reading
===============

See the `official documentation <https://github.com/doctrine-extensions/DoctrineExtensions/blob/main/doc/sluggable.md>`_
for use of this behavior.


.. role:: raw-html(raw)
   :format: html

.. include:: footer.rst
