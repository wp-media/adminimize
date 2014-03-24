jQuery(document).ready( function() {

	/**
	 * Define the option areas we want to tweak, needs to be updated each time we add a new custom content type.
	 * This could be done away with if there were proper ids on the containers, instead of #poststuff for everything
	 */
	var areas = ['Admin Bar options',
		'Global options',
		'Dashboard options',
		'Menu Options',
		'Write options - Post',
		'Write options - Page',
		'Write options - Footer Contact Infos',
		'Write options - MSVUs Logo',
		'Write options - For Students',
		'Write options - Quotes',
		'Write options - What is Food Security',
		'Widget options',
		'WP Nav Menu options',
		'Write options - MSVU Link - Sidebar'];

	for (var i = 0; i < areas.length; i++) {

		jQuery('#_mw_adminimize_options').find("h3:contains('" + areas[i] + "')").parent().parent().find('table thead').first().find('th').each(function(x) {

			if ( x > 0 ) { // Skip the label column

				var userRole = jQuery(this).html().replace('Deactivate for<br>', '');
				
				/*
				 * do some tweaking to line column headers up with input names.
				 * this could also be done away with if the checkbox names were derived from the role in the heading
				 **/
				if ( 'Event Contributor' == userRole) {

					userRole = 'ai1ec_event_assistant';

				} else if ( 'BackWPup jobs helper' == userRole) {

					userRole = 'backwpup_helper';

				} else if ( 'BackWPup jobs checker' == userRole) {

					userRole = 'backwpup_check';

				} else {

					userRole = userRole.replace(' ', '_').toLowerCase();

				}

				jQuery(this).toggle(function() {
					jQuery(this).parent().parent().parent().find("input[name*='" + userRole + "']").prop('checked', true);
				}, function() {
					jQuery(this).parent().parent().parent().find("input[name*='" + userRole + "']").prop('checked', false);
				});
				
			}
		
		});
	}
});