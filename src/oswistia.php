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

use Alledia\Framework\Joomla\Extension\AbstractPlugin;
use Joomla\Registry\Registry;

defined('_JEXEC') or die();

require_once 'include.php';

if (defined('ALLEDIA_FRAMEWORK_LOADED')) {
    /**
     * OSWistia Content Plugin
     *
     */
    class PlgContentOSWistia extends AbstractPlugin
    {
        public function __construct(&$subject, $config = array())
        {
            $this->namespace = 'OSWistia';

            parent::__construct($subject, $config);
        }

        /**
         * @param string $context
         * @param object $article
         * @param object $params
         * @param int    $page
         *
         * @return bool
         */
        public function onContentPrepare($context, &$article, &$params, $page = 0)
        {
            if (JString::strpos($article->text, '{wistia') === false) {
                return true;
            }

            $this->init();

            $contentParams = new Registry($params);

            $defaultParams = clone($this->params);
            $defaultParams->merge($contentParams);

            $content = new Alledia\Framework\Content\Text($article->text, $defaultParams);
            $tags = $content->getPluginTags('wistia');

            if (!empty($tags)) {
                foreach ($tags as $tag) {
                    $videoId = $tag->getContent();

                    if (!empty($videoId)) {
                        // Merge the default params
                        $tagDefaultParams = clone($defaultParams);
                        $tagDefaultParams->merge($tag->params);
                        $tag->params = $tagDefaultParams;

                        if ($this->isPro()) {
                            $embed = new Alledia\OSWistia\Pro\Embed($videoId, $tag->params);
                        } else {
                            $embed = new Alledia\OSWistia\Free\Embed($videoId, $tag->params);
                        }

                        // Replace the tag with the embed code
                        $article->text = str_replace($tag->toString(), $embed->toString(), $article->text);
                    }
                }
            }

            return true;
        }
    }
}
