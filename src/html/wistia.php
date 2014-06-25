<?php
/**
 * @package   plg_content_wistiaembed
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2014 Open Source Training, LLC. All rights reserved
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
    public static function embed($videoID, $options = array())
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
            $isFocusEnabled = false;
            if (isset($options['focus']) && $options['focus'] === true) {
                $isFocusEnabled = true;

                $pluginList['dimthelights'] = array(
                    'src' => WISTIA_PLUGINS_PATH . '/dimthelights.min.js'
                );
            }

            // Video size
            if (!isset($options['width'])) {
                $options['width'] = 800;
            }

            if (!isset($options['height'])) {
                $options['height'] = 600;
            }

            $html = "<div";
            $html .= " id=\"wistia_" . $videoID . "\"";
            $html .= " class=\"wistia_embed\"";
            $html .= " style=\"width:" . $options["width"] . "px; height:" . $options["height"] . "px;\"";
            $html .= "></div>\n";
            $html .= "<script charset=\"ISO-8859-1\" src=\"//fast.wistia.com/assets/external/E-v1.js\"></script>\n";
            $html .= "<script>\n";
            $html .= "    window.wistiaEmbed = Wistia.embed(\"" . $videoID . "\", {\n";
            $html .= "        autoPlay: " . ($isAutoplayEnabled ? "true" : "false") . ",\n";
            $html .= "        playerPreference: \"html5\",\n";
            $html .= "        captions: " . ($isCaptionsEnabled ? "true" : "false") . ",\n";
            $html .= "        focus: " . ($isFocusEnabled ? "true" : "false") . ",\n";
            $html .= "        plugin: " . json_encode($pluginList) . "\n";
            $html .= "    });\n";
            $html .= "</script>\n";
        }

        return $html;
    }
}
