<?php

namespace NotificationChannels\Gitter\Test;

use Mockery;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Illuminate\Notifications\Notification;
use NotificationChannels\Gitter\GitterChannel;
use NotificationChannels\Gitter\GitterMessage;
use NotificationChannels\Gitter\Exceptions\CouldNotSendNotification;

class GitterChannelTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    public function test_it_can_send_a_notification(): void
    {
        $client = Mockery::mock(Client::class);

        $client->shouldReceive('post')->once()
            ->with(
                'https://api.gitter.im/v1/rooms/:room/chatMessages',
                [
                    'json'    => ['text' => 'hello'],
                    'headers' => ['Authorization' => 'Bearer :token'],
                ]
            )->andReturn(new Response(200));

        $channel = new GitterChannel($client);
        $channel->send(new TestNotifiable(), new TestNotification());
    }

    public function test_it_does_not_send_a_message_when_room_missed(): void
    {
        $this->expectException(CouldNotSendNotification::class);

        $channel = new GitterChannel(new Client());
        $channel->send(new TestNotifiable(), new TestNotificationWithMissedRoom());
    }

    public function test_it_does_not_send_a_message_when_from_missed(): void
    {
        $this->expectException(CouldNotSendNotification::class);

        $channel = new GitterChannel(new Client());
        $channel->send(new TestNotifiable(), new TestNotificationWithMissedFrom());
    }
}

class TestNotifiable
{
    public function routeNotificationFor()
    {
        return '';
    }
}

class TestNotification extends Notification
{
    public function toGitter()
    {
        return GitterMessage::create('hello')->room(':room')->from(':token');
    }
}

class TestNotificationWithMissedRoom extends Notification
{
    public function toGitter()
    {
        return GitterMessage::create('hello')->from(':token');
    }
}

class TestNotificationWithMissedFrom extends Notification
{
    public function toGitter()
    {
        return GitterMessage::create('hello')->room(':room');
    }
}
