wpplugin_weblinks
=================
Developer: Oskar  (www.oscarperez.es)

Tested up to: 4.0

Stable tag: 1.0

License: GPLv2 or later

== Description ==

This plugin helps to manage the list of url's easily over the WordPress blog.

Language: Spanish

The opg_plugin_weblinks database have three columns:

-idLink INT( 11 ) ,

-name VARCHAR( 100 ) NOT NULL ,

-url VARCHAR( 140 ) NOT NULL )';


The plugin contains four files:
- opg_weblinks.php
- opg_weblinks.js
- img/modificar.png
- img/papelera.png

== Installation ==

Unzip the Weblinks plugin into your blog, into the path wp-content\plugins.
After the installation, you will must have a new directory: wp-content\plugins\opg_weblinks

Activate it, 
You're done!

== Changelog ==

= 1.0.0 = *Release Date - 9th December, 2014
= 1.1.0 = *Release Date - 2rd January, 2015
    In the list of links changed the literal 'Modify' and 'Delete' by two images.
    Before deleting the record, a confirmation is requested by a JavaScript confirm.