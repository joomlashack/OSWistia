<?php
/**
 * @package   OSWistia
 * @contact   www.joomlashack.com, help@joomlashack.com
 * @copyright 2016-2019 Joomlashack.com. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 * This file is part of OSWistia.
 *
 * OSWistia is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * OSWistia is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OSWistia.  If not, see <http://www.gnu.org/licenses/>.
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
