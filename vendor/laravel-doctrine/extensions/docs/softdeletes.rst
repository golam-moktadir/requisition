============
Soft Deletes
============

SoftDeleteable behavior allows to "soft delete" objects, filtering them at
SELECT time by marking them as with a timestamp, but not explicitly removing
them from the database.

* Works with DQL DELETE queries (using a Query Hint).
* All SELECT queries will be filtered, not matter from where they are
  executed (Repositories, DQL SELECT queries, etc).
* Can be nested with other behaviors.
* Annotation, Yaml and Xml mapping support for extensions.
* Support for 'timeAware' option: When creating an entity set a date of
  deletion in the future and never worry about cleaning up at expiration time.


Installation
============

Add ``LaravelDoctrine\Extensions\SoftDeletes\SoftDeleteableExtension``
to ``doctrine.extensions`` config in ``config/doctrine.php``.


Further Reading
===============

See the `official documentation <https://github.com/doctrine-extensions/DoctrineExtensions/blob/main/doc/softdeleteable.md>`_
for use of this behavior.


.. role:: raw-html(raw)
   :format: html

.. include:: footer.rst
