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

    public function __construct(string $text, string $title, int $priority = self::PRIORITY_HIGH, int $ttl = 3600, array $payload = []);

    public function setTitle(string $title): void;

    public function getTitle(): string;

    public function setText(string $text): void;

    public function getText(): string;

    public function setPriority(int $priority): void;

    public function getPriority(): int;

    public function setTTL(int $ttl): void;

    public function getTTL(): int;

    public function setPayload(array $payload): void;

    public function getPayload(): array;
}