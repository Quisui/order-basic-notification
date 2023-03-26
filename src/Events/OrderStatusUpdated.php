<?php

namespace Quisui\OrderBasicNotification\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Response;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Quisui\OrderBasicNotification\Exceptions\CustomValidationException;

class OrderStatusUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;

    public function __construct(array $data)
    {
        if (!Schema::hasTable('orders')) {
            throw new CustomValidationException('In order to use this package you need to have the order table', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validator = Validator::make($data, [
            'order_uuid' => 'required',
            'new_status' => 'required|max:255',
            'timestamp' => 'required',
            'webhook_url' => 'required|url',
            'webhook_http_method' => 'required|string'
        ]);

        if ($validator->fails()) {
            throw new CustomValidationException($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
