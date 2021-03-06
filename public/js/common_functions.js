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

// For now this only handles the orders for support and restaurant sidebar.
function update_counter_js(data) {

    let counts = JSON.parse(data);
    if (counts.restaurantActiveOrders) {
        $('#sidebar .orders #restaurantActiveOrders').text(counts.restaurantActiveOrders);
        console.log("restaurantActiveOrders : " + counts.restaurantActiveOrders)
    }
    else {
        $('#sidebar .orders #tatalOrdersCount').text((counts.activeOrders + counts.waitingOrders));
        $('#sidebar .orders #activeOrdersCount').text(counts.activeOrders);
        $('#sidebar .orders #waitingOrdersCount').text(counts.waitingOrders);
    }
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
    var audio = new Audio('/audio/short_notification.mp3');
    audio.play()
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
