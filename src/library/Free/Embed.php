<?php
/**
 * @package   plg_content_wistiaembed
 * @contact   www.alledia.com, hello@alledia.com
 * @copyright 2016 Alledia.com, All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace Alledia\OSWistia\Free;

use stdClass;

defined('_JEXEC') or die();

class Embed
{
    /**
     * The video id
     *
     * @var string
     */
    protected $videoId;

    /**
     * The embed params
     *
     * @var \JRegistry
     */
    protected $params;

    public function __construct($videoId, $params)
    {
        $this->videoId = $videoId;
        $this->params  = $params;
    }

    /**
     * Create the Wistia embed code using the embed API.
     *
     * @return string
     */
    public function toString()
    {
        $html = '';

        if (!empty($this->videoId)) {
            $width        = $this->params->get("width", 425);
            $height       = $this->params->get("height", 344);
            $shortVideoId = substr($this->videoId, 0, 3);
            $embedOptions = json_encode($this->getEmbedOptions());

            $html = "<div";
            $html .= " id=\"wistia_{$this->videoId}\"";
            $html .= " class=\"wistia_embed wistia_async_{$this->videoId}\"";
            $html .= " style=\"width:{$width}px; height:{$height}px;\"";
            $html .= "></div>\n";
            $html .= "<script src=\"//fast.wistia.com/assets/external/E-v1.js\" async></script>\n";
            $html .= "<script>\n";
            $html .= "    window._wq = window._wq || []; _wq.push({\"{$shortVideoId}\": {$embedOptions}});\n";
            $html .= "    _wq.push({id: \"{$shortVideoId}\", onReady: function(video) {window.wistiaEmbed = video;}});\n";
            $html .= "</script>\n";
        }

        return $html;
    }

    /**
     * Return the embed options as object
     *
     * @return string
     */
    protected function getEmbedOptions()
    {
        $options = new stdClass;

        $options->videoFoam = (bool) $this->params->get('responsive', true);

        return $options;
    }
}
