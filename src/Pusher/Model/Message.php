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

    public function __construct(string $text, int $priority = MessageInterface::PRIORITY_HIGH)
    {
        $this->text = $text;
    }

    public function setText(string $text)
    {
        $this->text = $text;
    }

    public function getText():string
    {
        return $this->text;
    }

    public function setPriority(int $priority)
    {
        $this->priority = $priority;
    }

    public function getPriority():int
    {
        return (int)$this->priority;
    }
}