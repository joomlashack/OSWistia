/**
 * @package   OSWistia
 * @contact   www.alledia.com, hello@alledia.com
 * @copyright 2014 Alledia.com, All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
/* global Wistia */

Wistia.plugin("fixautoplayresumable", function(video) {

    var firstPlay = true;

    function resumableKey() {
        return [video.params.pageUrl || location.href, video.hashedId(), "resume_time"];
    }

    function resumeTime() {
        return Wistia.localStorage(resumableKey());
    }

    function play() {
        if (resumeTime() > 0 && firstPlay) {
            video.pause();
        }

        firstPlay = false;
    }

    video.bind("play", play);

    return {};
});
