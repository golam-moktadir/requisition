============
Introduction
============

This package contains extensions for Doctrine that hook into the facilities
of Doctrine and offer new functionality and tools to use Doctrine
more efficiently. This package contains mostly used behaviors which can be
easily attached to the event system of Doctrine to handle records being
flushed in a behavioral way.

Behavioral extensions (Gedmo)
=============================

See the `doctrine-extensions documentation <https://github.com/doctrine-extensions/DoctrineExtensions>`_
for more information.

* ``Blameable`` - updates string or reference fields on create, update, and
  even property change with a string or object (e.g. user).
* ``IpTraceable`` - inherited from Timestampable, sets IP address instead
  of timestamp.
* ``Loggable`` - helps tracking changes and history of objects, also supports
  version management.
* ``Sluggable`` - urlizes your specified fields into single unique slug.
* ``SoftDeleteable`` - allows to implicitly remove records.
* ``Sortable`` - makes any document or entity sortable.
* ``Timestampable`` - updates date fields on create, update, and even
  property change.
* ``Translatable`` - gives you a handy solution for translating records into
  different languages. Easy to setup, easier to use.
* ``Tree`` - this extension automates the tree handling process and adds
  some tree specific functions on repository. (closure, nestedset or
  materialized path).
* ``Uploadable`` - provides file upload handling in entity fields.


Query/Type Extensions (Beberlei)
================================

A set of extensions for Doctrine that add support for additional functions
available in MySQL and Oracle.

.. list-table:: Query/Type Extensions
   :widths: 25 75
   :header-rows: 1

   * - Database
     - Functions
   * - MySQL
     - ``ACOS``, ``ASCII``, ``ASIN``, ``ATAN``, ``ATAN2``, ``BINARY``, ``CEIL``,
       ``CHAR_LENGTH``, ``CONCAT_WS``, ``COS``, ``COT``, ``COUNTIF``, ``CRC32``,
       ``DATE``, ``DATE_FORMAT``, ``DATEADD``, ``DATEDIFF``, ``DATESUB``,
       ``DAY``, ``DAYNAME``, ``DEGREES``, ``FIELD``, ``FIND_IN_SET``, ``FLOOR``,
       ``FROM_UNIXTIME``, ``GROUP_CONCAT``, ``HOUR``, ``IFELSE``, ``IFNULL``,
       ``LAST_DAY``, ``MATCH_AGAINST``, ``MD5``, ``MINUTE``, ``MONTH``,
       ``MONTHNAME``, ``NULLIF``, ``PI``, ``POWER``, ``QUARTER``, ``RADIANS``,
       ``RAND``, ``REGEXP``, ``REPLACE``, ``ROUND``, ``SECOND``, ``SHA1``,
       ``SHA2``, ``SIN``, ``SOUNDEX``, ``STD``, ``STRTODATE``,
       ``SUBSTRING_INDEX``, ``TAN``, ``TIME``, ``TIMESTAMPADD``,
       ``TIMESTAMPDIFF``, ``UUID_SHORT``, ``WEEK``, ``WEEKDAY``, ``YEAR``
   * - Oracle
     - ``DAY``, ``MONTH``, ``NVL``, ``TODATE``, ``TRUNC``, ``YEAR``
   * - Sqlite
     - ``DATE``, ``MINUTE``, ``HOUR``, ``DAY``, ``WEEK``, ``WEEKDAY``,
       ``MONTH``, ``YEAR``, ``STRFTIME*``


.. role:: raw-html(raw)
   :format: html

.. include:: footer.rst
