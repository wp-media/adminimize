jQuery( document ).ready( function( $ ) {
	// close postboxes that should be closed
//	$('.if-js-closed').removeClass( 'if-js-closed' ).addClass('closed');
	// postboxes setup
	// pagenow variable is set by WordPress
	postboxes.add_postbox_toggles( pagenow, {} );
} );