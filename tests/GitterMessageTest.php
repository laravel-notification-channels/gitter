<?php

namespace NotificationChannels\Gitter\Test;

use PHPUnit\Framework\TestCase;
use NotificationChannels\Gitter\GitterMessage;

class GitterMessageTest extends TestCase
{
    public function test_it_can_accept_a_content_when_constructing_a_message(): void
    {
        $message = new GitterMessage('hello');

        $this->assertEquals('hello', $message->content);
    }

    public function test_it_can_accept_a_content_when_creating_a_message(): void
    {
        $message = GitterMessage::create('hello');

        $this->assertEquals('hello', $message->content);
    }

    public function test_it_can_set_the_content(): void
    {
        $message = (new GitterMessage())->content('hello');

        $this->assertEquals('hello', $message->content);
    }

    public function test_it_can_set_the_room(): void
    {
        $message = (new GitterMessage())->room('room');

        $this->assertEquals('room', $message->room);
    }

    public function test_it_can_set_the_from(): void
    {
        $message = (new GitterMessage())->from('token');

        $this->assertEquals('token', $message->from);
    }
}
