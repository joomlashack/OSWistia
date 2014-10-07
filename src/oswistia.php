<?php
/**
 * @package   OSWistia
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2013-2014 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die();

jimport('joomla.plugin.plugin');

require_once 'include.php';
require_once "helper.php";

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
        if (JString::strpos($article->text, '{wistia') === false) {
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

        return true;
    }
}
