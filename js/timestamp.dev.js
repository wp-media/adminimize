/**
 * Auto-Show "Published On" form element on post pages.
 */
addLoadEvent(function(){
	jQuery('.edit-timestamp').click(function () {
		if (jQuery('#timestampdiv').is(":hidden")) {
			jQuery('#timestampdiv').slideDown("normal");
			jQuery('.edit-timestamp').hide();
		}
		return false;
	}).click();
});

