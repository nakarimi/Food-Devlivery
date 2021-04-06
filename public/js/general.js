/*
Author       : Naser Nikzad
Description  : Js codes related general pages.
*/

jQuery(function ($) {
    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Item stock toggle for available or N/A.
        $('.itemStockStatus').change(function() {
        	let item_id = $(this).attr('item_id');
        	let url = '/updateItemStockStatus';
        	let message = 'موجودیت غذا تغیر داده شد.';
        	item_status_update_ajax_request(item_id, url, message);        	
	    });

	    // Item stock toggle for available or N/A.
        $('.menuStockStatus').change(function() {
        	let item_id = $(this).attr('item_id');
        	let url = '/updateMenuStockStatus';
        	let message = 'موجودیت مینو تغیر داده شد.';
        	item_status_update_ajax_request(item_id, url, message);        	
	    });

	    function item_status_update_ajax_request(item_id, url, message) {
	    	if (item_id > 0) {
        		$.ajax({
	                type: 'POST',
	                url: url,
	                data: {item_id:item_id},
	                success: function () {
	                    show_message([message, 'success']);
	                },
	                error: function (e) {
	                    js_error_callback();
	                }
	            });
        	} 
        	else {
        		show_message(['مشکلی پیش آمده است.', 'danger']);
        	}
	    }
    });
});
