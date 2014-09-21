/**
 * timestamp open
 */
addLoadEvent(function(){
	open_timestamp();
	jQuery('.edit-timestamp').click();
});

function open_timestamp() {
	jQuery('.edit-timestamp').click(function () {
		if ( jQuery('#timestampdiv').is(":hidden") ) {
			jQuery('#timestampdiv').slideDown("normal");
			jQuery('.edit-timestamp').hide();
		}
		return false;
	});
}
