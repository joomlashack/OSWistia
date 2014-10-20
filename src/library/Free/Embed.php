<?php
/**
 * @package   plg_content_wistiaembed
 * @contact   www.alledia.com, hello@alledia.com
 * @copyright 2014 Alledia.com, All rights reserved
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
            $width  = $this->params->get("width", 425);
            $height = $this->params->get("height", 344);

            $embedOptions = json_encode($this->getEmbedOptions());

            $html = "<div";
            $html .= " id=\"wistia_{$this->videoId}\"";
            $html .= " class=\"wistia_embed\"";
            $html .= " style=\"width:{$width}px; height:{$height}px;\"";
            $html .= "></div>\n";
            $html .= "<script charset=\"ISO-8859-1\" src=\"//fast.wistia.com/assets/external/E-v1.js\"></script>\n";
            $html .= "<script>\n";
            $html .= "    window.wistiaEmbed = Wistia.embed(\"{$this->videoId}\", {$embedOptions});\n";
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
