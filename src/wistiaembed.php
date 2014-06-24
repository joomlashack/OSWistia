<?php
/**
 * @package   plg_content_wistiaembed
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2014 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

defined('_JEXEC') or die();

jimport('joomla.plugin.plugin');

require_once "helper.php";

/**
 * Wistia Video Embedder Content Plugin
 *
 */
class plgContentWistiaEmbed extends JPlugin
{
    public function __construct(&$subject, $config = array())
    {
        parent::__construct($subject, $config);

        $lang = JFactory::getLanguage();
        $lang->load('plg_content_wistiaembed.sys', __DIR__);
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

        $tags = plgContentWistiaEmbedHelper::extractWistiaTagsFromText($article->text);

        if (!empty($tags)) {
            foreach ($tags as $tag) {
                // Extract ID and params from the tag
                $tagData = plgContentWistiaEmbedHelper::parseWistiaTag($tag);

                if (!empty($tagData->id)) {
                    // Get the embed code
                    $embed = JHtml::_('wistia.embed', $tagData->id, $tagData->params);

                    // Replace the tag with the embed code
                    $article->text = str_replace($tag, $embed, $article->text);
                }
            }
        }

        return true;
    }
}
