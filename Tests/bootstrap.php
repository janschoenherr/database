<?php

/**
 * @copyright  (C) 2013 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// phpcs:ignoreFile
/*
 * Ensure that required path constants are defined.  These can be overridden within the phpunit.xml file
 * if you chose to create a custom version of that file.
 */
if (!\defined('JPATH_ROOT')) {
    \define('JPATH_ROOT', \realpath(\dirname(__DIR__)));
}

// Search for the Composer autoload file
$composerAutoload = \dirname(__DIR__) . '/vendor/autoload.php';

if (\file_exists($composerAutoload)) {
    include_once $composerAutoload;
}
