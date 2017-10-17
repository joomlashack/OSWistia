<?php
/**
 * @package   OSWistia
 * @contact   www.joomlashack.com, help@joomlashack.com
 * @copyright 2016 Joomlashack.com, All rights reserved
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die();

if (!defined('OSWISTIA_PLUGIN_PATH')) {
    define('OSWISTIA_PLUGIN_PATH', __DIR__);
}

if (!defined('OSWISTIA_MEDIA_URL')) {
    $baseURL = preg_replace('#http[s]?://#i', '//', JURI::root());
    define('OSWISTIA_MEDIA_URL', $baseURL . 'media/plg_content_oswistia');
}

// Alledia Framework
if (!defined('ALLEDIA_FRAMEWORK_LOADED')) {
    $allediaFrameworkPath = JPATH_SITE . '/libraries/allediaframework/include.php';

    if (file_exists($allediaFrameworkPath)) {
        require_once $allediaFrameworkPath;
    } else {
        JFactory::getApplication()
            ->enqueueMessage('[OSWistia] Alledia framework not found', 'error');
    }
}
