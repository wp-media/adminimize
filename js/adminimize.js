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

    // Close every box other than the first, to keep the page clean.
    $('.postbox:not(:first)').addClass('closed');

    // Open the box when the user clicks the shortcut
    $('#minimenu a').on('click',function(e){
		var ID = $(this).attr('href');
		$(ID).closest( '.postbox' ).removeClass('closed');
	});

    // Scroll to top
	$('.adminimize-scroltop').on('click',function(e){
		e.preventDefault();
        $('html,body').animate({scrollTop:0},700);
	});

	// Adminimize export switch
	$('#adminimize-toggle').on('click', function(e){
		var value = $(this).attr('checked');
		if ( value == 'checked'){
			$('#adminimize-export-role').css('display', 'none');
			$('#adminimize-export').css('display', 'block');
		} else {
			$('#adminimize-export-role').css('display', 'flex');
			$('#adminimize-export').css('display', 'none');
		}
	});
	
	$('#mw_adminimize_export_select_roles').select2({
		width: '100%'
	});

} );