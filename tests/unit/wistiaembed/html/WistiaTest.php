<?php
/**
 * @package   test_simplerenew
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2014 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

class WistiaEmbedHtmlWistiaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test get the embed code without configuration, using the default
     * params.
     */
    public function testEmbedNoConfig()
    {
        $videoID = 'du3a1yf8q598a';

        $embedCode = JHTML::_('wistia.embed', $videoID);

        $this->assertStringEqualsFile(
            realpath(OSWISTIA_MOCK . '/embed_default_code.html'),
            $embedCode,
            "Wrong default embed code"
        );
    }

    /**
     * Test get the embed code with custom size
     */
    public function testEmbedCustomSizeConfig()
    {
        $videoID = 'du3a1yf8q598a';
        $options = array(
            'width'  => 900,
            'height' => 562
        );

        $embedCode = JHTML::_('wistia.embed', $videoID, $options);

        $this->assertStringEqualsFile(
            realpath(OSWISTIA_MOCK . '/embed_custom_size.html'),
            $embedCode,
            "Wrong embed code for custom size"
        );
    }

    /**
     * Test get the embed code with autoplay enabled
     */
    public function testAutoplayConfig()
    {
        $videoID = 'du3a1yf8q598a';
        $options = array(
            'autoplay' => true
        );

        $embedCode = JHTML::_('wistia.embed', $videoID, $options);

        $this->assertStringEqualsFile(
            realpath(OSWISTIA_MOCK . '/embed_autoplay.html'),
            $embedCode,
            "Wrong embed code for autoplay enabled"
        );
    }

    /**
     * Test get the embed code with resumabled enabled
     */
    public function testResumableConfig()
    {
        $videoID = 'du3a1yf8q598a';
        $options = array(
            'resumable' => true
        );

        $embedCode = JHTML::_('wistia.embed', $videoID, $options);

        $this->assertStringEqualsFile(
            realpath(OSWISTIA_MOCK . '/embed_resumable.html'),
            $embedCode,
            "Wrong embed code for resumable enabled"
        );
    }

    /**
     * Test get the embed code with autoplay and resumable enabled
     */
    public function testAutoplayResumableConfig()
    {
        $videoID = 'du3a1yf8q598a';
        $options = array(
            'autoplay' => true,
            'resumable' => true
        );

        $embedCode = JHTML::_('wistia.embed', $videoID, $options);

        $this->assertStringEqualsFile(
            realpath(OSWISTIA_MOCK . '/embed_autoplay_resumable.html'),
            $embedCode,
            "Wrong embed code for autoplay and resumable enabled"
        );
    }

    /**
     * Test get the embed code with captions enabled
     */
    public function testCaptionsConfig()
    {
        $videoID = 'du3a1yf8q598a';
        $options = array(
            'captions' => true
        );

        $embedCode = JHTML::_('wistia.embed', $videoID, $options);

        $this->assertStringEqualsFile(
            realpath(OSWISTIA_MOCK . '/embed_captions.html'),
            $embedCode,
            "Wrong embed code for captions enabled"
        );
    }

    /**
     * Test get the embed code with focus enabled
     */
    public function testFocusConfig()
    {
        $videoID = 'du3a1yf8q598a';
        $options = array(
            'focus' => true
        );

        $embedCode = JHTML::_('wistia.embed', $videoID, $options);

        $this->assertStringEqualsFile(
            realpath(OSWISTIA_MOCK . '/embed_focus.html'),
            $embedCode,
            "Wrong embed code for focus enabled"
        );
    }
}
