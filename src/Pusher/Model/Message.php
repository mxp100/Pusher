<?php
/**
 * Created by PhpStorm.
 * User: yuriy
 * Date: 3/17/17
 * Time: 2:40 PM
 */

namespace Pusher\Model;


class Message implements MessageInterface
{
    protected $text;
    protected $title;
    protected $priority;
    protected $ttl;
    protected $payload;

    /**
     * Message constructor.
     *
     * @param string $text Message
     * @param string $title Title of message
     * @param int $priority Message priority
     * @param int $ttl Message TTL
     * @param array $payload Custom data
     */
    public function __construct(
        string $text,
        string $title,
        int $priority = MessageInterface::PRIORITY_HIGH,
        int $ttl = 3600,
        array $payload = []
    ) {
        $this->title = $title;
        $this->text = $text;
        $this->priority = $priority;
        $this->ttl = $ttl;
        $this->payload = $payload;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }

    public function getPriority(): int
    {
        return (int)$this->priority;
    }

    public function setTTL(int $ttl): void
    {
        $this->ttl = $ttl;
    }

    public function getTTL(): int
    {
        return $this->ttl;
    }

    public function setPayload(array $payload): void
    {
        $this->payload = $payload;
    }

    public function getPayLoad():array
    {
        return  $this->payload;
    }
}