<?php
/**
 * Created by PhpStorm.
 * User: yuriy
 * Date: 3/17/17
 * Time: 1:56 PM
 */

namespace Pusher\Adapter;


use Pusher\Collection\DeviceCollection;
use Pusher\Model\MessageInterface;

interface AdapterInterface
{
    const ENVIRONMENT_DEVELOPMENT = 0;
    const ENVIRONMENT_PRODUCTION = 1;

    public function __construct(string $serverKey, int $environment = AdapterInterface::ENVIRONMENT_DEVELOPMENT);

    public function push(DeviceCollection $devices, MessageInterface $message);

    public function getFeedback():array;
}