<?php

namespace Quisui\OrderBasicNotification\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Quisui\OrderBasicNotification\Events\OrderStatusUpdated;

class SendOrderStatusNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderStatusUpdated $event): void
    {
        $data = $event->data;

        $notification = [
            "order_uuid" => $data['order_uuid'],
            "new_status" => $data['new_status'],
            "timestamp" => $data['timestamp'],
            // ...
        ];
        $httpMethod = $data['webhook_http_method'];
        // Send the notification via webhook
        $url = $data['webhook_url'];
        $client = new \GuzzleHttp\Client();
        $client->$httpMethod($url, ['json' => $notification]);
    }
}
