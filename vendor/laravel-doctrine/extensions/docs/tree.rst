================
Tree - Nestedset
================

Tree nested behavior will implement the standard Nested-Set behavior on your
Entity. Tree supports different strategies. Currently it supports nested-set,
closure-table and materialized-path. Also this behavior can be nested with
other extensions to translate or generated slugs of your tree nodes.

* Materialized Path strategy for ORM.
* Closure tree strategy, may be faster in some cases where ordering does
  not matter.
* Support for multiple roots in nested-set.
* No need for other managers, implementation is through event listener.
* Synchronization of left, right values is automatic.
* Can support concurrent flush with many objects being persisted and updated.
* Can be nested with other extensions.


Further Reading
===============

See the `official documentation <https://github.com/doctrine-extensions/DoctrineExtensions/blob/main/doc/tree.md>`_
for use of this behavior.


.. role:: raw-html(raw)
   :format: html

.. include:: footer.rst
