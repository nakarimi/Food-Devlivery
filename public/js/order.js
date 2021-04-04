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
                    show_message("Devlivery requested Successfully!")
                },
                error: function (e) {
                    alert("js error in order.js file.")
                    
                }
            });
        });
    });
})
