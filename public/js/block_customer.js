jQuery(function ($) {
    $(document).ready(function() {
        var branch_id = '';
        var customer_id = '';
        var blocked = '';
        $(document).on('click','.customer_detials',function(){
            
            branch_id = $(this).attr('branch_id');
            customer_id = $(this).attr('customer_id');
            blocked = $(this).attr('blocked');
            customer_name = $(this).text();
            $('#customer_details_modal').modal({
                show: true
            });
        })

        $('#customer_details_modal').on('show.bs.modal', function(){
            var modal = $(this)
            modal.find('.modal-body #branch_id').val(branch_id);
            modal.find('.modal-body #customer_id').val(customer_id);
        });

        // If no reason provided, then avoid the process.
        $('#customer_details_modal form').on('submit', function(){
            if($('#blocking_note').val().length < 2) { alert('علت درخواست الزامی است.'); return false; } return true;
        });
    });
})
