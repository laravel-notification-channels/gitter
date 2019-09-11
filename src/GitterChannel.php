<?php

namespace NotificationChannels\Gitter;

use Exception;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Notifications\Notification;
use NotificationChannels\Gitter\Exceptions\CouldNotSendNotification;

class GitterChannel
{
    protected $baseUrl = 'https://api.gitter.im/v1/rooms';

    /** @var HttpClient */
    protected $httpClient;

    public function __construct(HttpClient $client)
    {
        $this->httpClient = $client;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  Notification  $notification
     *
     * @return void
     */
    public function send($notifiable, Notification $notification): void
    {
        /** @var GitterMessage $message */
        $message = $notification->{'toGitter'}($notifiable);

        if (empty($message->room)) {
            $message->room($notifiable->routeNotificationFor('gitter'));
        }

        $this->sendMessage($message);
    }

    protected function sendMessage(GitterMessage $message): void
    {
        if (empty($message->room)) {
            throw CouldNotSendNotification::missingRoom();
        }

        if (empty($message->from)) {
            throw CouldNotSendNotification::missingFrom();
        }

        $options = [
            'json'    => ['text' => $message->content],
            'headers' => [
                'Authorization' => "Bearer {$message->from}",
            ],
        ];

        try {
            $this->httpClient->post("{$this->baseUrl}/{$message->room}/chatMessages", $options);
        } catch (ClientException $exception) {
            throw CouldNotSendNotification::gitterRespondedWithAnError($exception);
        } catch (Exception $exception) {
            throw CouldNotSendNotification::couldNotCommunicateWithGitter($exception);
        }
    }
}
