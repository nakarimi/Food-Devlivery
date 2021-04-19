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
        $('#main_category_id').change(function() {
            let type = $(this).val();
            
            $.ajax({
                type: 'GET',
                url:'/loadCategory',
                data: {type:type},
                success: function (res) {
                    console.log('Cateogries are loaded2');
                    console.log(res);
                },
                error: function (e) {
                    alert("js error in order.js file.")
                    
                }
            });

        	 	
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
	    };

        $(document).on('click','.reject_branch_update',function(){
            $('#open_reject_form input[name=branch_detail_id]').val($(this).attr('branch_detail_id'));
        });

        $(document).on('click','#open_reject_form #sumit_branch_reject_btn',function(){
            let detail_id =  $('#open_reject_form input[name=branch_detail_id]').val();
            let reason =  $('#open_reject_form textarea[name=note]').val();
            
            // IF correct values are not provided.
            if (!detail_id > 0) {
                alert("صفحه را مججد لود کنید.");
                return;
            }
            let msg = ['سفارش توسط شما رد شد.', 'danger'];

            $.ajax({
                type: 'POST',
                url:'/rejectBranch',
                data: {detail_id:detail_id, reason: reason},
                success: function () {
                    show_message(msg);
                },
                error: function (e) {
                    alert("js error in order.js file.")
                    
                }
            });
        });
        // Wait until user do some interaction. https://stackoverflow.com/a/51657751/7995302
        setTimeout(() => {
            // {{-- This silent audio is added here for this reason https://stackoverflow.com/a/52228983/7995302 --}}
            if (document.getElementById('audio')) {
                document.getElementById('audio').play();
            }
        }, 5000)
        
        

    });
});
