<?php
/**
 * @package   tests_oswistia
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2014 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

/**
 * Set variables below as needed for your installation
 */

/**
 * Paths to test libraries and assets.
 *
 * joomla: Must point to a locally installed version of joomla
 */
$testPaths = array(
    'joomla' => '/var/www'
);

// It isn't clear if this is needed. But it was recommended. We'll figure it out later
$_SERVER['HTTP_HOST']      = 'ostraining.dev';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI']    = '';
