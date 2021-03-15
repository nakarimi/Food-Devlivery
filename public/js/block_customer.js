jQuery(function ($) {
    $(document).ready(function() {
        var customer_email = '';
        var customer_name = '';
        var branch_id = '';
        var customer_id = '';
        var order_id = '';
        var timer = 0;
        var blocked = '';
        $(document).on('click','.customer_detials',function(){
            customer_email = $(this).attr('customer_email');
            branch_id = $(this).attr('branch_id');
            customer_id = $(this).attr('customer_id');
            order_id = $(this).attr('order_id');
            blocked = $(this).attr('blocked');
            customer_name = $(this).text();
            $('#customer_details_modal').modal({
                show: true
            });
        })

        $('#customer_details_modal').on('show.bs.modal', function(){
            var modal = $(this)
            // alert(customer_email);
            // Set values in edit popup
            // var action = '/event_category/'+id;
            // $("#edit_department_form").attr("action", action);
            modal.find('.modal-body #customerName').text(customer_name);
            modal.find('.modal-body #customerEmail').text(customer_email);
            modal.find('.modal-body #branch_id').val(branch_id);
            modal.find('.modal-body #customer_id').val(customer_id);
            modal.find('.modal-body #order_id').val(order_id);
        })

        $('#open_reason_button').click(function () {
            if (blocked != 1){
                timer++;
                $('#textarea_div').removeClass('d-none');
                $('#textarea_div textarea').focus();
                if(timer > 1){
                    var note = $('#blocking_note').val();
                    $('#note').val(note);
                    $('#block_form').submit();
                }
            }
        });
    });
})
