<?php

namespace Quisui\OrderBasicNotification;

use Quisui\OrderBasicNotification\Events\OrderStatusUpdated;

class NotificationOrderStatusUpdater
{
    public function __construct(array $data, array ...$options)
    {
        event(new OrderStatusUpdated($data));
    }
}
