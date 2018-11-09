/**
 * Adminimize script to select all checkbox for each rows.
 * Only load and usage on settings page.
 *
 * @version 2015-12-20
 */
jQuery(document).ready(function ($) {
    'use strict';

    $('a.nav-tab').click(function () {
        $('a.nav-tab').each(function () {
            $(this).removeClass('nav-tab-active');
        });

        $('div.tab-content').each(function () {
            $(this).addClass('hidden');
        });

        var tabKey = $(this).data('tab');
        $('div#tab-' + tabKey).removeClass('hidden');
        $(this).addClass('nav-tab-active');

        return false;
    });

    $('thead input:checkbox').change(function () {

        var className = this.className,
            input = 'input:checkbox.' + className;

        $(input).prop(
            'checked',
            $(this).prop('checked')
        );
    });

    $('.postbox h3').on('click', function ( e ) {
        $(this).closest('.postbox').toggleClass('closed');
        e.preventDefault();
    });

});
