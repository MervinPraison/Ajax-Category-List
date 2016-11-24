jQuery( document ).ready(function() {
jQuery( document ).on( 'click', '#cat-list-button-id', function() {
	var post_id = jQuery(this).data('id-list');	
	var category_name = jQuery(this).data('category-list');
	jQuery.ajax({
		url : categorylist.ajax_url,
		type : 'post',
		data : {
			action : 'ajax_category_list',
			post_id : post_id,
			category_name : category_name
		},
		success : function( response ) {
			jQuery('#cat-display').html( response );
		}
	});

	return false;
});

});
