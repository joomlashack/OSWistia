<?php
/**
 * @package   test_simplerenew
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2014 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

class PlgContentWistiaEmbedTest extends \PHPUnit_Framework_TestCase
{
    private function prepareContent($text, $pluginParams = array())
    {
        $dispatcher = JDispatcher::getInstance();

        if (empty($pluginParams)) {
            $pluginParams = '{"width":"800","height":"600","responsive":"1"}';
        } else {
            if (is_object($pluginParams) || is_array($pluginParams)) {
                $pluginParams = json_encode($pluginParams);
            }
        }

        $config = array(
            'type' => 'content',
            'name' => 'wistiaembed',
            'params' => $pluginParams
        );

        $params = null;

        $article = new stdClass;
        $article->text = $text;

        $plugin = new plgContentWistiaEmbed($dispatcher, $config);
        $plugin->onContentPrepare('com_content.article', $article, $params);

        return $article->text;
    }

    /**
     * Test get the embed code without configuration, using the default
     * params.
     */
    public function testEmbedNoConfig()
    {
        $content = '{wistia}du3a1yf8q598a{/wistia}';

        $preparedContent = $this->prepareContent($content);

        $this->assertStringEqualsFile(
            realpath(OSWISTIA_MOCK . '/embed_default_code.html'),
            $preparedContent,
            "Wrong default embed code"
        );
    }

    /**
     * Test get the embed code using the plugin wistiafollows
     * params.
     */
    public function testEmbedPluginFollow()
    {
        $content = '{wistia plugins="{\"wistiafollows\":{\"src\":\"http:\/\/fast.wistia.com\/labs\/twitter-follows\/wistia-follows.js\",\"postRoll\":{\"screenName\":\"ostraining\",\"showScreenName\":true},\"people\":[{\"screenName\":\"ostraining\",\"start\":10,\"end\":20}]}}"}du3a1yf8q598a{/wistia}';

        $preparedContent = $this->prepareContent($content);

        $this->assertStringEqualsFile(
            realpath(OSWISTIA_MOCK . '/embed_plugin_wistiafollows.html'),
            $preparedContent,
            "Wrong embed code for the wistiafollows plugin"
        );
    }

    // TODO: Test with multiple tags
}
