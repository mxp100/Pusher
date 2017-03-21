<?php
/**
 * Created by PhpStorm.
 * User: yuriy
 * Date: 3/17/17
 * Time: 2:44 PM
 */

namespace Pusher\Model;


interface MessageInterface
{
    const PRIORITY_NORMAL = 0;
    const PRIORITY_HIGH = 1;

    public function __construct(string $text, int $priority = self::PRIORITY_HIGH);

    public function setText(string $text);

    public function getText():string;

    public function setPriority(int $priority);

    public function getPriority():int;
}