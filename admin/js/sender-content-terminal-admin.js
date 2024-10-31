(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$(window).load(function () {

		if ($("input#content-terminal-user-has-not-accepted").length) {
			tb_show("User Agreement", "#TB_inline?width=600&height=200&inlineId=user-agreement-modal&title=UserAgreement");
		}else{
			if ($("input#content-terminal-token-missing").length) {
				tb_show("Plugin Token", "#TB_inline?width=600&height=200&inlineId=add-token-modal&title=PluginToken");
			}
		}
	});

	$(function () {

		$("#sender_content_terminal_save_token").submit(function (e) {
			e.preventDefault();
			var data = {
				'action': $(this).attr('action'),
				'plugin_token': $(this).find('.plugin_token').val()
			};
			jQuery.post(ajaxurl, data, function (response) {
				alert('Your plugin token was: ' + response);
				location.reload();
			});
		});

		$("#content-terminal-button-accept").click(function (e) {
			e.preventDefault();
			var data = {
				'action': $(this).attr('action'),
				'accepted': true
			};
			jQuery.post(ajaxurl, data, function (response) {
				alert(response);
				location.reload();
			});
		});

	});

})( jQuery );
