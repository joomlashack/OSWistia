<?php
/**
 * @package   OSWistia
 * @contact   www.alledia.com, hello@alledia.com
 * @copyright 2014 Alledia.com, All rights reserved
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

use Alledia\Joomla\Extension\AbstractPlugin;

defined('_JEXEC') or die();

require_once 'include.php';

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

                    if ($this->isPro()) {
                        // $embed = OSWistiaEmbed::call_your_method_here();
                    }

                    // Replace the tag with the embed code
                    $article->text = str_replace($tag, $embed, $article->text);
                }
            }
        }

        return true;
    }
}
