============
Translatable
============

Translatable behavior offers a very handy solution for translating specific
record fields in different languages. Further more, it loads the translations
automatically for a locale currently used, which can be set to Translatable
Listener on it`s initialization or later for other cases through the Entity
itself.

Features:

* Automatic storage of translations in database.
* Automatic translation of Entity or Document fields then loaded.
* ORM query can use **hint** to translate all records without
  issuing additional queries.
* Can be nested with other behaviors.
* Annotation, Yaml and Xml mapping support for extensions.


Further Reading
===============

See the `official documentation <https://github.com/doctrine-extensions/DoctrineExtensions/blob/main/doc/translatable.md>`_
for use of this behavior.


.. role:: raw-html(raw)
   :format: html

.. include:: footer.rst
