<?php
/**
 * @package   OSWistia
 * @contact   www.alledia.com, hello@alledia.com
 * @copyright 2014 Alledia.com, All rights reserved
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

use Alledia\Framework\Joomla\Extension\AbstractPlugin;

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

            $params = new JRegistry($this->params);

            $content = new Alledia\Framework\Content\Text($article->text);
            $tags = $content->getTags('wistia');

            if (!empty($tags)) {
                foreach ($tags as $tag) {
                    $videoId = $tag->getContent();

                    if (!empty($videoId)) {
                        // Merge the default params
                        $tag->params = $this->params->merge($tag->params);

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
