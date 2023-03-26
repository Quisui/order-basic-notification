# Challenge order basic notification system
Buckhill Challenge create a package for notifications - NotificationOrderStatusUpdater
### To ensure that we will not miss any sale and to keep track of the statuses of the orders a basic notification service needs to be created.
# Installation
> composer require quisui/order-basic-notification
# Usage
1) create a new NotificationOrderStatusUpdater object on any part of your code
2) When you have your object prepared 
```
<?php
    use Quisui\OrderBasicNotification\NotificationOrderStatusUpdater; 
    new NotificationOrderStatusUpdater(...);
?>
```
3) This wil require some required parameters as an array
```
<?php
        $required = [
            'order_uuid' => rand(0, 666),
            'new_status' => 'status',
            'timestamp' => 'dateTimeRequired',
            'webhook_url' => 'https://webhook.site/81872e1a-5b97-4a0d-b32a-55365cc1b774',
            'webhook_http_method' => 'post'
        ]
        use Quisui\OrderBasicNotification\NotificationOrderStatusUpdater; 
        new NotificationOrderStatusUpdater($required);
?>
```

Now everytime that you want to consume any webhook url and send specific information you can use this basic event listener that will send your information
