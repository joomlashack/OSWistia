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

        JHtml::addIncludePath(__DIR__ . '/html');
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
        if (JString::strpos($article->text, '{wistia') === false or JString::strpos($article->text, '.wistia.com/medias/') === false) {
            return true;
        }

        $tags = plgContentWistiaEmbedHelper::extractWistiaTagsFromText($article->text);

        if (!empty($tags)) {
            foreach ($tags as $tag) {
                // Extract ID and params from the tag
                $tagData = plgContentWistiaEmbedHelper::parseWistiaTag($tag, $params);

                if (!empty($tagData->id)) {

                    $useIframe = false;
                    if (is_object($params)) {
                        $useIframe = $params->get('iframe', false);
                    } elseif (is_array($params)) {
                        $useIframe = isset($params['iframe']) && $params['iframe'];
                    }

                    if ($useIframe) {
                        // Get the iframe code
                        $embed = JHtml::_('wistia.iframe', $tagData->id);
                    } else {
                        // Get the embed code
                        $embed = JHtml::_('wistia.embed', $tagData->id, $tagData->params);
                    }

                    // Replace the tag with the embed code
                    $article->text = str_replace($tag, $embed, $article->text);
                }
            }
        }

        $article->text = preg_replace(
            '|(http://([a-zA-Z0-9_-]+).wistia.com/medias/([a-zA-Z0-9_-]+))|e',
            '$this->wistiaCodeEmbed("\3")',
            $article->text
        );

        return true;
    }

    protected function wistiaCodeEmbed($vCode)
    {

        $width  = 425;
        $height = 344;

        $output .= '<iframe width="' . $width . '" height="' . $height . '" frameborder="0" src="http://fast.wistia.net/embed/iframe/' . $vCode . '"></iframe>';

        return $output;
    }

}
