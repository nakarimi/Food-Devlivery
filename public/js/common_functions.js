/*
Author       : Naser Nikzad
Description  : Js codes related to orders.
*/

function show_message(msg) {

    let message = '<div id="success-alert" class="alert alert-'+msg[1]+' alert-dismissible fade show" role="alert"><strong>'+msg[0]+'</strong></div>';

    if ($('div.alertDiv').length) {
            $('div.alertDiv').html(message);
    }
    else {
        $('div.header').after('<div class="alertDiv">'+message+'</div>');
    }

    hide_message();

}

// Hide the normal alert.
function hide_message() {
    $(".alertDiv").fadeTo(2000, 500).slideUp(500, function(){
        $(".alertDiv").slideUp(2500, function() { $(this).remove(); });
    });
};

function js_error_callback() {
    alert("js error in order.js file.")
    console.trace();
}

// New order sound
function playSound() {
    var audioElement = document.createElement('audio');
    audioElement.setAttribute('src', '/audio/short_notification.mp3');
    audioElement.play();
}

$(document).on('change','#audio',function(){

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

console.log("common file loaded");
