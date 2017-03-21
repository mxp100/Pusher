<?php
/**
 * Created by PhpStorm.
 * User: yuriy
 * Date: 3/17/17
 * Time: 2:07 PM
 */

namespace Pusher\Collection;


use Pusher\Model\DeviceInterface;

/**
 * Class DeviceCollection
 *
 * @package Pusher\Collection
 */
class DeviceCollection extends AbstractCollection
{
    public function add(DeviceInterface $device)
    {
        $this->collection[$device->getToken()] = $device;
    }

    public function getTokens()
    {
        $tokens = [];
        foreach ($this as $device) {
            /** @var DeviceInterface $device */
            $tokens[] = $device->getToken();
        }
        return $tokens;
    }
}