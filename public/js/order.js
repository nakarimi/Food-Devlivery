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

            order_status_ajax_request(order_id, status, customer_id, null);
        });
        
        $('#order_approve_btn').on('click',function(event){
            $('#order_approved_form #order_id').val($(this).attr('order_id'));
            $('#order_approved_form #customer_id').val($(this).attr('customer_id'));
        });

        $('#order_approved_form_submit_btn').on('click',function(event){

            // Avoid form redirect on submit.
            event.preventDefault();
            let order_id = $('#order_approved_form #order_id').val();
            let promissed_time =  $('#order_approved_form #promissed_time').val();
            let customer_id =  $('#order_approved_form #customer_id').val();
            let status =  'processing';
            
            // IF correct values are not provided.
            if (!order_id > 0 || !customer_id > 0) {
                return;
            }
            order_status_ajax_request(order_id, status, customer_id, promissed_time);
            
        });

        function order_status_ajax_request(order_id, status, customer_id, promissed_time = null) {
            $.ajax({
                type: 'POST',
                url:'/updateOrderStatus',
                data: {order_id:order_id, status: status, customer_id:customer_id, promissed_time: promissed_time},
                success: function (promissed_time) {
                    show_message("سفارش توسط شما قبول شد، زمان تحویل دهی " + promissed_time);
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
            console.log("delivery requsted!");
            let order_id = $(this).attr('order_id');
            $.ajax({
                type: 'POST',
                url:'/requestDelivery',
                data: {order_id:order_id},
                success: function (data) {
                    $(this).closest('span').replaceWith('<span class="badge bg-inverse-primary">(Company Delivery) <br><span class="badge bg-inverse-danger">Pending</span></span>');
                    show_message("Devlivery requested Successfully!")
                },
                error: function (e) {
                    alert("js error in order.js file.")
                    
                }
            });
        });
    });
})
