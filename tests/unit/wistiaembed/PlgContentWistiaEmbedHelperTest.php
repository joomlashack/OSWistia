<?php
/**
 * @package   test_simplerenew
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2014 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

class PlgContentWistiaEmbedHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test get the video id from a valid {wistia} tag without params.
     */
    public function testGetVideoIdValidTagNoParams()
    {
        $expectedId = 'du3a1yf8q598a';
        $code = '{wistia}' . $expectedId . '{/wistia}';

        $actualId = plgContentWistiaEmbedHelper::getVideoID($code);

        $this->assertEquals($expectedId, $actualId, "Wrong video id");
    }

    /**
     * Test get the video id from a valid {wistia} tag without params but with
     * white spaces between the tags and the id.
     */
    public function testGetVideoIdValidTagNoParamsWithSpaces()
    {
        $expectedId = 'du3a1yf8q598a';
        $code = '{wistia}   ' . $expectedId . ' {/wistia}';

        $actualId = plgContentWistiaEmbedHelper::getVideoID($code);

        $this->assertEquals($expectedId, $actualId, "Wrong video id");
    }

    /**
     * Test get the video id from a valid {wistia} tag with an id with non
     * alfanumeric chars, like symbols and accents.
     */
    public function testGetVideoIdStrangeChars()
    {
        $expectedId = 'du3a1yf8q598a?*#$%^@\\/|:";\'.,`~áãàéíóü=+-_()';
        $code = '{wistia}' . $expectedId . '{/wistia}';

        $actualId = plgContentWistiaEmbedHelper::getVideoID($code);

        $this->assertEquals($expectedId, $actualId, "Wrong video id");
    }

    /**
     * Test get the video id from a valid {wistia} tag with valid params
     */
    public function testGetVideoIdValidTagParams()
    {
        $expectedId = 'du3a1yf8q598a';
        $code = '{wistia param1="value1" param2="value2" param3="value3"}' . $expectedId . '{/wistia}';

        $actualId = plgContentWistiaEmbedHelper::getVideoID($code);

        $this->assertEquals($expectedId, $actualId, "Wrong video id");

    }

    /**
     * Test get the video id from a valid iframe embed code
     */
    public function testGetVideoIdValidIframeComEmbed()
    {
        $expectedId = 'du3a1yf8q598a';

        $iframeEmbed = '<iframe src="//home.wistia.com/medias/' . $expectedId
            . '?plugin%5Bresumable%5D%5Bsrc%5D=%2F%2Ffast.wistia.com%2Flabs%2Fresumable%2Fplugin.js'
            . '&plugin%5Bresumable%5D%5Basync%5D=false"
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
                height="572"></iframe>';

        $actualId = plgContentWistiaEmbedHelper::getVideoID($iframeEmbed);

        $this->assertEquals($expectedId, $actualId);
    }

    /**
     * Test get the video id from a valid iframe embed code
     */
    public function testGetVideoIdValidIframeNetEmbed()
    {
        $expectedId = 'du3a1yf8q598a';

        $iframeEmbed = '<iframe src="//fast.wistia.net/embed/iframe/' . $expectedId
            . '?plugin%5Bresumable%5D%5Bsrc%5D=%2F%2Ffast.wistia.com%2Flabs%2Fresumable%2Fplugin.js'
            . '&plugin%5Bresumable%5D%5Basync%5D=false"
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
                height="572"></iframe>';

        $actualId = plgContentWistiaEmbedHelper::getVideoID($iframeEmbed);

        $this->assertEquals($expectedId, $actualId);
    }

    /**
     * Test get the video id from a valid iframe embed code
     */
    public function testGetVideoIdValidIframeShortMediasEmbed()
    {
        $expectedId = 'du3a1yf8q598a';

        $iframeEmbed = '<iframe src="//wi.st/medias/' . $expectedId
            . '?plugin%5Bresumable%5D%5Bsrc%5D=%2F%2Ffast.wistia.com%2Flabs%2Fresumable%2Fplugin.js'
            . '&plugin%5Bresumable%5D%5Basync%5D=false"
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
                height="572"></iframe>';

        $actualId = plgContentWistiaEmbedHelper::getVideoID($iframeEmbed);

        $this->assertEquals($expectedId, $actualId);
    }

    /**
     * Test get the video id from a valid iframe embed code
     */
    public function testGetVideoIdValidIframeShortEmbed()
    {
        $expectedId = 'du3a1yf8q598a';

        $iframeEmbed = '<iframe src="//wi.st/embed/' . $expectedId
            . '?plugin%5Bresumable%5D%5Bsrc%5D=%2F%2Ffast.wistia.com%2Flabs%2Fresumable%2Fplugin.js'
            . '&plugin%5Bresumable%5D%5Basync%5D=false"
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
                height="572"></iframe>';

        $actualId = plgContentWistiaEmbedHelper::getVideoID($iframeEmbed);

        $this->assertEquals($expectedId, $actualId);
    }

    /**
     * Test get the video id from an invalid tag. It should return an empty value.
     */
    public function testGetVideoIdInvalidTag()
    {
        $code = '{wstia}8786asd6a76da{/wistia}';

        $actualId = plgContentWistiaEmbedHelper::getVideoID($code);

        $this->assertEmpty($actualId, 'No empty value for an invalid tag');
    }

    /**
     * Test get the video id from an invalid iframe embed. It should return
     * an empty value.
     */
    public function testGetVideoIdInvalidIframeEmbed()
    {
        $iframeEmbed = '<iframe src="//youtube.com/embed/7abc9sd8sa6'
            . '?plugin%5Bresumable%5D%5Bsrc%5D=%2F%2Ffast.wistia.com%2Flabs%2Fresumable%2Fplugin.js'
            . '&plugin%5Bresumable%5D%5Basync%5D=false"
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
                height="572"></iframe>';

        $actualId = plgContentWistiaEmbedHelper::getVideoID($iframeEmbed);

        $this->assertEmpty($actualId);
    }

    /**
     * Test get the video id from a valid tag plus html code. It should return the id.
     */
    public function testGetVideoIdValidTagPlusHtml()
    {
        $expectedId = '8786asd6a76da';

        $code = '<div id="myvideocontainer" clas="container">';
        $code .= '{wistia}' . $expectedId . '{/wistia}';
        $code .= '</div><p>A few words 9a8sdfahhj1...</p>';

        $actualId = plgContentWistiaEmbedHelper::getVideoID($code);

        $this->assertEquals($expectedId, $actualId, "Wrong video id");
    }

    // TODO: add more tests for the new methods
}
