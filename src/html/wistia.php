<?php
/**
 * @package   plg_content_wistiaembed
 * @contact   www.alledia.com, hello@alledia.com
 * @copyright 2014 Alledia.com, All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

defined('_JEXEC') or die();

define('WISTIA_PLUGINS_BASEURL', preg_replace('#http[s]?://#i', '//', JURI::base()));
define('WISTIA_PLUGINS_PATH', WISTIA_PLUGINS_BASEURL . 'media/plg_content_wistiaembed/js/plugins/min');

abstract class JHtmlWistia
{
    /**
     * Create the Wistia embed code using the embed API.
     *
     * @param string  $videoID The wistia video ID
     * @param array   $options An array with options
     *
     * @return string
     */
    public static function embed($videoID, $options = array(), $settings = array())
    {
        $html = '';

        if (!empty($videoID)) {
            $pluginList = array();

            // Resumable plugin
            $isResumableEnabled = false;
            if (isset($options['resumable']) && $options['resumable'] === true) {
                $isResumableEnabled = true;

                // Add the resumable plugin
                $pluginList['resumable'] = array(
                    'src'   => WISTIA_PLUGINS_PATH . '/resumable.min.js',
                    'async' => false
                );
            }

            // Add a fix for the autoplay and resumable work better together
            $isAutoplayEnabled = false;
            if (isset($options['autoplay']) && $options['autoplay'] === true) {
                $isAutoplayEnabled = true;

                if ($isResumableEnabled) {
                    $pluginList['fixautoplayresumable'] = array(
                        'src' => WISTIA_PLUGINS_PATH . '/fixautoplayresumable.min.js'
                    );
                }
            }

            // Closed Captions plugin
            $isCaptionsEnabled = (isset($options['captions']) && $options['captions'] === true);
            $pluginList['captions-v1'] = array(
                'onByDefault' => $isCaptionsEnabled
            );

            // Focus plugin
            $isFocusEnabled = (isset($options['focus']) && $options['focus'] === true);
            $pluginList['dimthelights'] = array(
                'src'     => WISTIA_PLUGINS_PATH . '/dimthelights.min.js',
                'autoDim' => $isFocusEnabled
            );

            $customPlugins = null;
            // Plugins manually set
            if (isset($options['plugins'])) {
                try {
                    $optionPlugins = stripslashes($options['plugins']);
                    $optionPlugins = preg_replace('/^"/', '', $optionPlugins);
                    $optionPlugins = preg_replace('/"$/', '', $optionPlugins);
                    $customPlugins = json_decode($optionPlugins, true);
                    unset($optionPlugins);
                } catch (Exception $e) {
                    // Ignore exceptions caused by invalid json param
                }
            }

            if (!empty($customPlugins)) {
                foreach ($customPlugins as $plugin => $pluginOptions) {
                    $pluginList[$plugin] = $pluginOptions;
                }
            }

            $html = "<div";
            $html .= " id=\"wistia_" . $videoID . "\"";
            $html .= " class=\"wistia_embed\"";
            $html .= " style=\"width:" . $settings["width"] . "px; height:" . $settings["height"] . "px;\"";
            $html .= "></div>\n";
            $html .= "<script charset=\"ISO-8859-1\" src=\"//fast.wistia.com/assets/external/E-v1.js\"></script>\n";
            $html .= "<script>\n";
            $html .= "    window.wistiaEmbed = Wistia.embed(\"" . $videoID . "\", {\n";
            $html .= "        autoPlay: " . ($isAutoplayEnabled ? "true" : "false") . ",\n";
            $html .= "        playerPreference: \"html5\",\n";
            $html .= "        captions: " . ($isCaptionsEnabled ? "true" : "false") . ",\n";
            $html .= "        focus: " . ($isFocusEnabled ? "true" : "false") . ",\n";
            $html .= "        plugin: " . json_encode($pluginList) . "\n";

            if (isset($options['volume'])) {
                $html .= "        ,volume: " . (float)$options['volume'] . "\n";
            }

            $html .= "    });\n";
            $html .= "</script>\n";
        }

        return $html;
    }

    /**
     * Create the Wistia embed code using the an iframe.
     *
     * @param string  $videoID The wistia video ID
     *
     * @return string
     */
    public static function iframe($videoID)
    {
        $html = '<iframe src="//fast.wistia.net/embed/iframe/' . $videoID . '?videoFoam=true"
            allowtransparency="true"
            frameborder="0"
            scrolling="no"
            class="wistia_embed"
            name="wistia_embed"
            allowfullscreen
            mozallowfullscreen
            webkitallowfullscreen
            oallowfullscreen
            msallowfullscreen
            width="900"
            height="506"
            ></iframe>';

        $html .= '<script src="//fast.wistia.net/assets/external/iframe-api-v1.js"></script>"';

        return $html;
    }
}
