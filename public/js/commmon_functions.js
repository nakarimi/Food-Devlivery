/*
Author       : Naser Nikzad
Description  : Js codes related to orders.
*/

function show_message(msg) {
    $('div.card-body').prepend('<div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert"><strong>'+msg+'</strong></div>');
    $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
        $("#success-alert").slideUp(500);
    });
}