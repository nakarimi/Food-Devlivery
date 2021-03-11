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
            let status = $(this).val();
            $.ajax({
                type: 'POST',
                url:'/updateOrderStatus',
                data: {order_id:order_id, status: status},
                success: function (data) {                
                    show_message("The Order status updated!.")
                },
                error: function (e) {
                    alert("js error in order.js file.")
                }
            });
        });

        function set_select_box_color(element) {
            element.attr('status', element.val())
        }

        $(document).on('change','#driver_id',function(){
            
            let order_id = $(this).attr('order_id');
            let driver_id = $(this).val();
            $.ajax({
                type: 'POST',
                url:'/assignDriver',
                data: {order_id:order_id, driver_id: driver_id},
                success: function (data) {                
                    show_message("The Order assigned to Driver!")
                },
                error: function (e) {
                    alert("js error in order.js file.")
                }
            });
        });
    });
})
