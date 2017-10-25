/**
 * @package   OSWistia
 * @contact   www.joomlashack.com, help@joomlashack.com
 * @copyright 2017 Wistia. All rights reserved
 */
/* global Wistia */

(function($, window) {
	window.OSWistiaInit = function(video) {
		function getButtonForSettingOnPlayer(setting, value) {
			return $('.w-menu__list-item--' + setting + ' li button[title="' + value + '"]');
		}

		/**
		 * Try to get elements with the legacy setting.
		 */
		function getButtonWithLegacySetting(setting, legacyValue) {
			return $('.w-menu__list-item--' + setting + ' li button[data-optionkey="' + legacyValue + '"]');
		}

		/**
		 * Try fixing the specific setting.
		 */
		function fixLegacySetting(settingName, setting, legacyValue) {
			// Check if we have any list item on the player with the stored settings
			var button = getButtonWithLegacySetting(settingName, legacyValue);
			if (typeof button != 'undefined') {
				// We found an item. We need to update the stored setting with the correct value.
				var newValue = $(button).attr('title');
				window.Wistia.localStorage(setting, newValue);
			}
		}

		/**
		 * Fix old settisngs format where we pulled the value from the data-optionkey attribute
		 * instead of the title attribute. The data-optionkey value changes from video to video
		 * even on the same video quality.
		 */
		function fixBackwardCompatibilitySetting() {
			// Get video and speed settings to check if we have legacy values to convert.
			var speed   = window.Wistia.localStorage('playbackRate'),
				quality = window.Wistia.localStorage('quality');

			fixLegacySetting('Speed', 'playbackRate', speed);
			fixLegacySetting('Quality', 'quality', quality);
		}

		// Timeout to wait until the button is there
		window.setTimeout(function() {
			fixBackwardCompatibilitySetting();

			// Add persistence do the video quality if set by the user.
			// Neighter Wistia or HTML5 don't provide an API to intercept
			// the change of the video quality. So we need to intercept the 
			// click events.
			$('.w-menu__list-item--Quality li button').click(function() {
				// Get the new quality
				var $button = $(this),
					quality = $button.attr('title');
				
				// Register the new value
				window.Wistia.localStorage('quality', quality);
			});

			// Make sure we are using the quality selected by the user
			var quality = window.Wistia.localStorage('quality'),
				$button = getButtonForSettingOnPlayer('Quality', quality);

			$button.trigger('click');


			// Add persistence do the video speed if set by the user.
			// Neighter Wistia or HTML5 don't provide an API to intercept
			// the change of the video speed. So we need to intercept the 
			// click events.
			$('.w-menu__list-item--Speed li button').click(function() {
				// Get the new speed
				var $button = $(this),
					speed = $button.attr('title');
				
				// Register the new value
				window.Wistia.localStorage('playbackRate', parseFloat(speed));
			});

			// Make sure we are using the speed selected by the user
			var speed = window.Wistia.localStorage('playbackRate'),
				$button = getButtonForSettingOnPlayer('Speed', speed);
			
			$button.trigger('click');
			
			// Use a timeout to make sure we override any default setting
			window.setTimeout(function() {
				video.playbackRate(parseFloat(speed));
			}, 500);
		}, 500);
	};
})(jQuery, window);