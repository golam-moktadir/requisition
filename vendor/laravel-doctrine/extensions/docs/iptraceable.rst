============
Ip Traceable
============

IpTraceable behavior will automate the update of IP trace on your Entities.
It works through annotations and can update fields on creation, update,
property subset update, or even on specific property value change.

* Automatic predefined ip field update on creation, update, property subset
  update, and even on record property changes.
* Specific annotations for properties, and no interface required.
* Can react to specific property or relation changes to specific value.
* Can be nested with other behaviors.
* Annotation, Yaml and Xml mapping support for extensions.


Installation
============

Add ``LaravelDoctrine\Extensions\IpTraceable\IpTraceableExtension``
to ``doctrine.extensions`` config in ``config/doctrine.php``.


Further Reading
===============

See the `official documentation <https://github.com/doctrine-extensions/DoctrineExtensions/blob/main/doc/ip_traceable.md>`_
for use of this behavior.


.. role:: raw-html(raw)
   :format: html

.. include:: footer.rst
