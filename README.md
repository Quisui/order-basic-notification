# Challenge order basic notification system
Buckhill Challenge create a package for notifications - NotificationOrderStatusUpdater
### To ensure that we will not miss any sale and to keep track of the statuses of the orders a basic notification service needs to be created.

# Information
The Observer pattern is implemented through events and listeners.
The package defines an event class called OrderStatusUpdated, which contains the data that will be passed to the listeners.
The package also defines a listener class called SendOrderStatusNotification, which is responsible for sending a notification to a webhook when an order status is updated.
The listener is registered to listen to the OrderStatusUpdated event in the EventServiceProvider class.
When the OrderStatusUpdated event is fired (usually through code that updates an order's status), the SendOrderStatusNotification listener is triggered and the notification is sent via a webhook.
This implementation allows other developers to easily add new listeners for the OrderStatusUpdated event if they want to perform additional actions when an order status is updated, without having to modify the existing code.

This method will make this listener available inside your app: <br> 
[src/ordernotificationprovider.php](https://github.com/Quisui/order-basic-notification/blob/master/src/OrderNotificationProvider.php) <br>
$this->app['events']->listen(
    'Quisui\OrderBasicNotification\Events\OrderStatusUpdated',
    'Quisui\OrderBasicNotification\Listeners\SendOrderStatusNotification'
);
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

#testing
To run your tests:
- navigate to your project with cd command: vendor/quisui/order-basic-notification
- now you will need to run:
  on your terminal: ../../vendor/bin/phpunit tests/OrderStatusUpdatedTest.php
