/**
 * Auto-Show "Published On" form element on post pages.
 */
addLoadEvent(function(){jQuery(".edit-timestamp").click(function(){return jQuery("#timestampdiv").is(":hidden")&&(jQuery("#timestampdiv").slideDown("normal"),jQuery(".edit-timestamp").hide()),!1}).click()});