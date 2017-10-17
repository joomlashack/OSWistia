/**
 * @package   OSWistia
 * @contact   www.joomlashack.com, help@joomlashack.com
 * @copyright 2017 Wistia. All rights reserved
 */
/* global Wistia */

(function($, window) {
	window.OSWistiaInit = function(video) {
		// Timeout to wait until the button is there
		window.setTimeout(function() {
			// Add persistence do the video quality if set by the user.
			// Neighter Wistia or HTML5 don't provide an API to intercept
			// the change of the video quality. So we need to intercept the 
			// click events.
			$('.w-menu__list-item--Quality li button').click(function() {
				// Get the new quality
				var $button = $(this),
					quality = $button.data('optionkey');
				
				// Register the new value
				window.Wistia.localStorage('oswistia_quality', quality);
			});

			// Make sure we are using the quality selected by the user
			var quality = window.Wistia.localStorage('oswistia_quality'),
				$button = $('.w-menu__list-item--Quality li button[data-optionkey="' + quality + '"]');

			$button.trigger('click');

		}, 1000);
	};
})(jQuery, window);