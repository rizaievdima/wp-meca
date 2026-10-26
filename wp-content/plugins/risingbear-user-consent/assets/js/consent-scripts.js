;(function($) {
	'use strict';
	const { __, _x, _n, _nx } = wp.i18n;
	$(document).ready(function($) {

		// create namespace to avoid any possible conflicts
		$.rb_uc_user_consent = {
			init: function() {
				$.rb_uc_user_consent.confirm_update();
				$.rb_uc_user_consent.actions_adding();
			},
			confirm_update: function() {
				$('form#rb_uc_update_plugin_options').on('submit', function(){
					return confirm("Are you sure you want to update the plugin settings?");
				});
			},
			actions_adding: function() {
				$('form#rb_uc_actions_adding').on('submit', function(e){
					e.preventDefault();
					if ( confirm("Are you sure you want to add action in header.php ?") === false )
						return;

					$('.actions-answers').html('');
					let data = $(this).serializeArray();
					data.push({
						name: 'action',
						value: 'rb_uc_actions_adding',
					});

					jQuery.post( ajaxurl, data, function( response ){
						let html = '<ul>';
						$.each(response, function(k,v){
							html += '<li class="'+v.status+'">'+v.message+'</li>'
						});
						html += '</ul>';

						$('.actions-answers').html(html);
					} );
				});
			},
		};

		$.rb_uc_user_consent.init();
	});
})(jQuery);