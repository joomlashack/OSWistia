<?php
/**
 * @package   plg_content_wistiaembed
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2014 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

abstract class plgContentWistiaEmbedHelper
{
    private static $wistiaTagRegex = '/\{wistia([\sa-z0-9\/="\{:\\\_!@#\'%&\-\*\+\|;\?<>\$,\.\}\[\]]*)}([^\{]*)\{\/wistia\}/i';

    /**
     * Parse the {wistia}{/wistia}/<iframe src="... tags returning the video ID
     * It can extract the video ID from the two forms of Wistia embedding
     * currently found in Guru - <iframe> and <div>/<script>
     *
     * @param string $code
     *
     * @return string
     */
    public static function getVideoID($code = '')
    {
        $id =  '';

        if (!empty($code)) {
            if (strpos($code, '{wistia') !== false) {
                // Check if the source has the "wistia" replacement tag
                if (preg_match(self::$wistiaTagRegex, $code, $match)) {
                    $id = trim($match[2]);
                }
            } elseif (strpos($code, 'wistia') !== false) {
                if (strpos($code, '<iframe ') !== false && preg_match('/src=["\'](.*?)["\']/', $code, $match)) {
                    $uri = new JURI($match[1]);

                    $validHosts = array('home.wistia.com', 'fast.wistia.net', 'wi.st');

                    if (in_array($uri->getHost(), $validHosts)) {
                        $id  = basename($uri->getPath());
                    }
                } elseif (strpos($code, 'Wistia.embed') !== false
                    && preg_match('/Wistia.embed\([\'"](.*?)[\'"]/', $code, $match)
                ) {
                    // Extract ID from javascript call to Wistia
                    $id = $match[1];
                }
            }
        }

        return $id;
    }

    /**
    * Parse and translate the recognized inline parameters
    *
    * @param $params string
    * @param $valid array associative array of valid inline parameters
    *
    * @return array
    */
    public static function parseParams($params, array $valid = array())
    {
        // Remove the wistia tag, extracting only the tag attributes
        $params = preg_replace('/^\{wistia/', '', $params);
        $params = preg_replace('/\}[a-z0-9\s]*\{\/wistia\}/', '', $params);

        // Parse the params
        $regex = '/([a-z0-9_]*)\s*=\s*([\sa-z0-9\/"\{:\\\_!@#\%&\-\*\+\|;\?<>\$,\.\}\[\]]*)"[\s]*/i';
        $parsed = array();
        if (preg_match_all($regex, $params, $vars)) {
            foreach ($vars[0] as $j => $var) {
                $param = $vars[1][$j];
                if (empty($valid)) {
                    $parsed[$param] = $vars[2][$j];
                } elseif (!empty($valid[$param])) {
                    $parsed[$valid[$param]] = $vars[2][$j];
                }
            }
        }

        return $parsed;
    }

    /**
     * Parse {wistia} tag string, returning the ID and attributes/params.
     *
     * @param  string $tag    The wistia tag
     * @param  array  $params An array with params to overwrite the params found on the tag
     * @return array          An array with ID and a list of params
     */
    public function parseWistiaTag($tag, $params = array())
    {
        $data = new stdClass;

        // Video ID
        $data->id = self::getVideoID($tag);

        // Params found on the tag
        $data->params = self::parseParams($tag);

        // Overwrite params with the params passed on runtime
        if (!empty($params)) {
            foreach ($params as $param => $value) {
                $data->params[$param] = $value;
            }
        }

        return $data;
    }

    /**
     * Extract multiple {wista} tags from a string
     *
     * @param  string $text A string with
     * @return array        An array with all {wista} tags found on the text
     */
    public function extractWistiaTagsFromText($text)
    {
        preg_match_all(self::$wistiaTagRegex, $text, $matches);

        return $matches[0];
    }
}
