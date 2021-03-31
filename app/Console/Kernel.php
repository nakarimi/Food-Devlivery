<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Order;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        
        // Here we check if there is any late order, orders that are not responded by customer during first minute of submitting.
        $schedule->call(function () {
        	
        	// Get time for one minute ago.
        	$oneMinuteAgo = Carbon::now()->subMinutes(1)->toDateTimeString();

        	// This is also to check if these are new orders, not the old one.
        	$threeMinuteAgo = Carbon::now()->subMinutes(3)->toDateTimeString();

        	// Check if there is any late order
            $ordersCount = Order::where('status', 'pending')->where('orders.created_at', '>', $threeMinuteAgo)->where('orders.created_at', '<', $oneMinuteAgo)->get()->count();
            if ($ordersCount) {
            	// Update waiting order list on front-end. 
            	event(new \App\Events\UpdateEvent('Late Order Detected!', NULL));
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
