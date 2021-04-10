/*
Author       : Naser Nikzad
Description  : Js codes related to orders.
*/

jQuery(function ($) {
    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('change','#order_status',function(){

            // Set correct color based on status.
            set_select_box_color($(this));

            let order_id = $(this).attr('order_id');
            let customer_id = $(this).attr('customer_id');
            let status = $(this).val();

            let message = ['Order of status update to ' + status, 'warning'];
            order_status_ajax_request(order_id, status, customer_id, message, null);
        });

        $(document).on('click','.order_approve_btn',function(){
            $('#order_approved_form #promissed_time').val('');
            $('#order_approved_form input[name=order_id]').val($(this).attr('order_id'));
            $('#order_approved_form input[name=customer_id]').val($(this).attr('customer_id'));
        });

        $(document).on('change','input#promissed_time',function(){
            $('#order_approved_form_submit_btn').css("pointer-events", 'auto');
        });

        $(document).on('click','#order_approved_form_submit_btn',function(){
            let order_id = $('#order_approved_form input[name=order_id]').val();
            let promissed_time =  $('#order_approved_form #promissed_time').val();
            let customer_id =  $('#order_approved_form input[name=customer_id]').val();
            let status =  'processing';

            // IF correct values are not provided.
            if (promissed_time.length < 1 || !order_id > 0 || !customer_id > 0) {
                alert("صفحه را مججد لود کنید.");
                return;
            }
            let message = ["سفارش توسط شما قبول شد.", 'success'];
            order_status_ajax_request(order_id, status, customer_id, message, promissed_time);
        });

        $(document).on('click','.order_reject_btn',function(){
            $('form#add_reject_reason input[name=order_id]').val($(this).attr('order_id'));
            $('form#add_reject_reason input[name=customer_id]').val($(this).attr('customer_id'));
        });

        $(document).on('click','form#add_reject_reason .add_reject_reason_btn',function(){
            let message = $(this).attr('message');
            let order_id =  $('form#add_reject_reason input[name=order_id]').val();
            let customer_id =  $('form#add_reject_reason input[name=customer_id]').val();
            let status =  'reject';
            
            // IF correct values are not provided.
            if (!order_id > 0 || !customer_id > 0) {
                alert("صفحه را مججد لود کنید.");
                return;
            }
            let msg = ['سفارش توسط شما رد شد.', 'danger'];
            order_status_ajax_request(order_id, status, customer_id, msg, message);
        });

        function order_status_ajax_request(order_id, status, customer_id, message, promissed_time = null) {
            $.ajax({
                type: 'POST',
                url:'/updateOrderStatus',
                data: {order_id:order_id, status: status, customer_id:customer_id, promissed_time: promissed_time},
                success: function () {
                    show_message(message);
                },
                error: function (e) {
                    alert("js error in order.js file.")
                    
                }
            });
        }
        
        function set_select_box_color(element) {
            element.attr('status', element.val())
        }

        $(document).on('change','#driver_id',function(){

            let order_id = $(this).attr('order_id');
            let customer_id = $(this).attr('customer_id');
            let driver_id = $(this).val();
            $.ajax({
                type: 'POST',
                url:'/assignDriver',
                data: {order_id:order_id, driver_id: driver_id, customer_id:customer_id},
                success: function (data) {
                    show_message("The Order assigned to Driver!")
                },
                error: function (e) {
                    alert("js error in order.js file.")
                    
                }
            });
        });

        $(document).on('click','.request_delivery_btn',function(){
            console.log("delivery requsted2!");
            let order_id = $(this).attr('order_id');
            $.ajax({
                type: 'POST',
                url:'/requestDelivery',
                data: {order_id:order_id},
                success: function (data) {
                    $(this).closest('span').replaceWith('<span class="badge bg-inverse-primary">(Company Delivery) <br><span class="badge bg-inverse-danger">Pending</span></span>');
                    show_message(["درخواست شما برای پیک فرستاده شد.", 'success']);
                },
                error: function (e) {
                    alert("js error in order.js file.")
                    
                }
            });
        });

        /**
         * Before update order form submission.
         * 
         * This snippet goes through newly and old
         * items in an order and update the final
         * contents object and put the data in a hidden
         * input and submits the form.
         */
        $('#web_order_update_form').on('submit', function(e){

            // Old items in the order.
            const old_items = $(this).data('contents').contents;

            // Empty array for holding newly selected items.
            let contents = new Array;

            // Counter used to identify item_$counter for each item.
            let counter = 0;

            // Loop through all the items for this branch.
            $('.items_in_order').each(function(){

                // If item is quantity of more than 0.
                if ($(this).val() > 0) {

                    // Empty array for determining if selected item already exists in the old items.
                    let already_exists = [];

                    // Gettin the enity of this selected item from it's data attribute.
                    const new_item = $(this).data('item');

                    // Loop through old items.
                    old_items.forEach(function(item, index){
                        // Getting the appropriate key of the object.
                        const key = Object.keys(item)[0];
                        
                        // Checking if currently selected item already exists in the old item.
                        if (item[key].item_id == new_item.item_id) {

                            // Push contents' object index and item's object index to the already_exists array.
                            already_exists.push(index, key);
                        }
                    });

                    // Attempting to save the selected item in an object.
                    const adding_new_item = {
                        ['item_' + [counter + 1]] : {

                            // Get the quantity from form.
                            'count': $(this).val(),
                            // Getting the price from old item, if old item existed for this.
                            // If not, we get the price for this newly selected item from it's entity.
                            'price': already_exists.length > 0 ? old_items[already_exists[0]][already_exists[1]].price: new_item.price,
                            'item_id' : new_item.item_id
                        }
                    }

                    // Push object of the item to the contents array.
                    contents.push(adding_new_item);
                    counter++;
                }
            });

            // Pushing the contents array in to the an object.
            const new_contents = {
                'contents' : contents
            }

            // Stringifying the final contents object and adding the related input as value in the form.
            $('[name="contents"]').val(JSON.stringify(new_contents));
        });

        // Support pick orders for following up.
        $(document).on('click','.followup',function(){

            let order_id = $(this).attr('order_id');
            let support_id =  $(this).attr('support_id');
            let cancel_id =  $(this).attr('cancelId');
            let message = ['Follow up canceled by you!', 'danger'];
            
            // IF process  was for canceling then just perform the request.
            if (!cancel_id > 0) {
                message = ['This order will be followed up by you!', 'success'];
                // IF correct values are not provided.
                if (!order_id > 0 || !support_id > 0) {
                    alert("Sorry, Please reload the page.");
                    return;
                }
            }

            $.ajax({
                type: 'POST',
                url:'/followupOrder',
                data: {order_id:order_id, support_id: support_id, cancel_id: cancel_id},
                success: function () {
                    show_message(message);
                },
                error: function (e) {
                    alert("js error in order.js file.")
                }
            });

        });
    });
})
