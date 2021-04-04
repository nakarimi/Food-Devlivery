/*
Author       : Naser Nikzad
Description  : Js codes related to orders.
*/

function show_message(msg) {
    $('div.header').after('<div class="alertDiv"><div id="success-alert" class="alert alert-'+msg[1]+' alert-dismissible fade show" role="alert"><strong>'+msg[0]+'</strong></div></div>');
    $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
        $("#success-alert").slideUp(2500);
    });

}

function js_error_callback() {
    alert("js error in order.js file.")
    console.trace();
}

$(document).on('click','.read-notification-button',function(event){
    var not_id =  $(this).attr('notification_id');
    var token = $("meta[name='csrf-token']").attr("content");
    // alert(not_id);
    $.ajax({
        type: 'put',
        url:'/mark_read_notifications',
        data:{id: not_id, _token: token },
        success: function (response) {
            if (not_id !== "all"){
                $( event.target ).closest( "li" )
                    .remove();
                $('#notification_number').text( function(i, oldval) {
                    return oldval-1;
                });
            }
            else {
                Livewire.emit('refreshNotifications');
            }
        },
        error: function (e) {
            console.log(e);
        }
    });
});
$(document).on('click', 'div.dropdown-menu.notifications', function (e) {
    e.stopPropagation();
});
