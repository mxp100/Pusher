<?php
/**
 * Created by PhpStorm.
 * User: yuriy
 * Date: 3/17/17
 * Time: 1:56 PM
 */

namespace Pusher\Adapter;


use Pusher\Collection\DeviceCollection;
use Pusher\Exception\AdapterException;
use Pusher\Model\MessageInterface;

interface AdapterInterface
{
    const ENVIRONMENT_DEVELOPMENT = 0;
    const ENVIRONMENT_PRODUCTION = 1;

    public function __construct(string $serverKey, int $environment = AdapterInterface::ENVIRONMENT_DEVELOPMENT);

    /**
     * Push message to devices
     * @param  DeviceCollection  $devices
     * @param  MessageInterface  $message
     * @throws AdapterException
     */
    public function push(DeviceCollection $devices, MessageInterface $message):void;

    /**
     * Get result of pushing
     * @return array
     * @throws AdapterException
     */
    public function getFeedback():array;
}