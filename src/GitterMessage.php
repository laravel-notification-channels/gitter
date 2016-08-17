<?php

namespace NotificationChannels\Gitter;

class GitterMessage
{
    /**
     * Gitter room id.
     *
     * @var string
     */
    public $room = '';

    /**
     * A user or app access token.
     *
     * @var string
     */
    public $from = '';

    /**
     * The text content of the message.
     *
     * @var string
     */
    public $content = '';

    /**
     * Create a new instance of GitterMessage.
     *
     * @param  string  $content
     *
     * @return static
     */
    public static function create($content = '')
    {
        return new static($content);
    }

    /**
     * Create a new instance of GitterMessage.
     *
     * @param $content
     */
    public function __construct($content = '')
    {
        $this->content($content);
    }

    /**
     * Set the Gitter room id to send message to.
     *
     * @param  string  $roomId
     *
     * @return $this
     */
    public function room($roomId)
    {
        $this->room = $roomId;

        return $this;
    }

    /**
     * Set the sender's access token.
     *
     * @param  string  $accessToken
     *
     * @return $this
     */
    public function from($accessToken)
    {
        $this->from = $accessToken;

        return $this;
    }

    /**
     * Set the content of the message. Supports GitHub flavoured markdown.
     *
     * @param  string  $content
     *
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }
}
