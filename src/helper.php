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
    protected static $video_id_cache_prefix = 'video-id-';

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
                if (preg_match('/\{wistia[^\}]*\}([^\{]*)\{\/wistia\}/', $code, $match)) {
                    $id = trim($match[1]);
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
        $params = str_replace('{wistia ', '', $params);
        $params = preg_replace('#}[^{]*{/wistia}#', '', $params);

        // Parse the params
        $regex = '/(.?)\s*=\s*[\'"](.+?)[\'"]\s*/';
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

    public function parseWistiaTag($tag)
    {
        $data = new stdClass;

        $data->id = self::getVideoID($tag);
        $data->params  = self::parseParams($tag);

        return $data;
    }

    public function extractWistiaTagsFromText($text)
    {
        preg_match_all('#\{wistia([^\}]*)\}[^\{]*\{\/wistia\}#i', $text, $matches);

        return $matches[0];
    }

    /**
     * Add the video id to the cache.
     * The cache is indexed by the current URL
     *
     * @param string $id
     */
    public static function setVideoIdCache($id)
    {
        $uri = JURI::getInstance();
        $hash = self::$video_id_cache_prefix . md5($uri->toString());

        JFactory::getSession()->set($hash, $id);
    }

    /**
     * Get the video id from the cache
     * The cache is indexed by the current URL
     *
     * @return string
     */
    public static function getCachedVideoId()
    {
        $uri = JURI::getInstance();
        $hash = self::$video_id_cache_prefix . md5($uri->toString());

        $id = JFactory::getSession()->get($hash, '');

        return $id;
    }
}
