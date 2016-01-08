/**
 * Adminimize script to select all checkbox for each rows.
 * Only load and usage on settings page.
 *
 * @version 2015-12-20
 */
jQuery( document ).ready( function( $ ) {
	'use strict';

	$( 'thead input:checkbox' ).change( function() {

		var className = this.className,
			input = 'input:checkbox.' + className;

		$( input ).prop(
			'checked', $( this ).prop( 'checked' )
		);
	} );

	$( '.postbox h3' ).on( 'click', function( e ) {
		$( this ).closest( '.postbox' ).toggleClass( 'closed' );
		e.preventDefault();
	} );

} );