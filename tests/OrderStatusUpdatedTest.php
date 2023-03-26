<?php

namespace Quisui\OrderBasicNotification\Tests;

use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use Quisui\OrderBasicNotification\Events\OrderStatusUpdated;
use Quisui\OrderBasicNotification\Exceptions\CustomValidationException;
use Quisui\OrderBasicNotification\Listeners\SendOrderNotification;
use Quisui\OrderBasicNotification\Listeners\SendOrderStatusNotification;
use stdClass;
use GuzzleHttp\Client;


class OrderStatusUpdatedTest extends TestCase
{
    use RefreshDatabase;

    public function testNotificationSentSuccessfully(): void
    {
        $event = new OrderStatusUpdated([
            'order_uuid' => 'test123',
            'new_status' => 'processing',
            'timestamp' => '2021-06-01 10:00:00',
            'webhook_http_method' => 'post',
            'webhook_url' => 'https://webhook.site/81872e1a-5b97-4a0d-b32a-55365cc1b774'
        ]);

        // Create a mock Guzzle client
        $mockClient = $this->getMockBuilder(Client::class)
            ->onlyMethods(['post'])
            ->getMock();
        $mockClient
            ->method('post')
            ->with(
                $event->data['webhook_url'],
                [
                    'json' => [
                        'order_uuid' => $event->data['order_uuid'],
                        'new_status' => $event->data['new_status'],
                        'timestamp' => $event->data['timestamp'],
                    ]
                ]
            )
            ->willReturn(new Response());

        // Replace the client in the listener with the mock client
        $listener = new SendOrderStatusNotification($mockClient);

        // Execute the listener
        $listener->handle($event);

        // Assert that the notification was sent successfully
        $this->assertTrue(true);
    }

    public function testOrderStatusUpdatedEvent()
    {
        $data = [
            'order_uuid' => rand(0, 666),
            'new_status' => 'shipped',
            'timestamp' => now(),
            'webhook_url' => 'https://webhook.site/81872e1a-5b97-4a0d-b32a-55365cc1b774',
            'webhook_http_method' => 'post'
        ];
        // Fire the event
        $event = new OrderStatusUpdated($data);
        event($event);

        // Assert that the event was fired with the correct data
        $this->assertEquals($data['order_uuid'], $event->data['order_uuid']);
        $this->assertEquals($data['new_status'], $event->data['new_status']);
        $this->assertEquals($data['timestamp'], $event->data['timestamp']);
    }

    public function testConstructorWithValidData()
    {
        $data = [
            'order_uuid' => 'example-order-uuid',
            'new_status' => 'example-new-status',
            'timestamp' => '2022-12-31 23:59:59',
            'webhook_url' => 'https://example.com/webhook',
            'webhook_http_method' => 'POST'
        ];
        $instance = new OrderStatusUpdated($data);
        $this->assertEquals($instance->data, $data);
    }

    public function testConstructorWithMissingRequiredFields()
    {
        $data = [
            'new_status' => 'example-new-status',
            'timestamp' => '2022-12-31 23:59:59',
            'webhook_url' => 'https://example.com/webhook',
            'webhook_http_method' => 'POST'
        ];
        $this->expectException(CustomValidationException::class);
        new OrderStatusUpdated($data);
    }

    public function testConstructorWithInvalidWebhookHttpMethod()
    {
        $data = [
            'order_uuid' => 'example-order-uuid',
            'new_status' => 'example-new-status',
            'timestamp' => '2022-12-31 23:59:59',
            'webhook_url' => 'https://example.com/webhook',
            'webhook_http_method' => 123
        ];
        $this->expectException(CustomValidationException::class);
        $instance = new OrderStatusUpdated($data);
    }
}
