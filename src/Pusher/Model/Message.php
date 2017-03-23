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
    protected $priority;
    protected $ttl;

    public function __construct(string $text, int $priority = MessageInterface::PRIORITY_HIGH, int $ttl = 3600)
    {
        $this->text = $text;
        $this->priority = $priority;
        $this->ttl = $ttl;
    }

    public function setText(string $text):void
    {
        $this->text = $text;
    }

    public function getText():string
    {
        return $this->text;
    }

    public function setPriority(int $priority):void
    {
        $this->priority = $priority;
    }

    public function getPriority():int
    {
        return (int) $this->priority;
    }

    public function setTTL(int $ttl):void
    {
        $this->ttl = $ttl;
    }

    public function getTTL():int
    {
        return $this->ttl;
    }
}