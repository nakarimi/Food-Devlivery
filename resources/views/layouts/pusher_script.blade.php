<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}'
    });

    var channel = pusher.subscribe('food-app-notification');
    channel.bind('notification-event', function(data) {
        if (data['message'] === "Notification" && userId == data.userId.id) {
            Livewire.emit('refreshNotifications');
        }
    });

    var channel = pusher.subscribe('food-app');
    channel.bind('update-event', function(data) {
        // Here we check if notification from pusher is about a new order, and is related to this user. Show message if it was, 
        if (JSON.stringify(data['message']) == '"New Order Recieved!"' && (userId == JSON.stringify(data['userId']))) {
            show_message(['سفارش جدید اضافه شد.', 'success']);
            playSound();
            
            // The message above is displayed, when user is on any page, but update of active orders should happened only when user is on the active orders page.
            if (window.location.pathname == '/activeOrders') {
                Livewire.emit('refreshActiveOrders');
            }
        }
        
    });
</script>