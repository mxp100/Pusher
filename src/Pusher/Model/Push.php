<?php
/**
 * Created by PhpStorm.
 * User: yuriy
 * Date: 3/17/17
 * Time: 3:39 PM
 */

namespace Pusher\Model;


use Pusher\Adapter\AdapterInterface;
use Pusher\Collection\DeviceCollection;

class Push
{
    private $adapter;
    private $message;
    private $devices;

    public function __construct(AdapterInterface $adapter, DeviceCollection $devices, MessageInterface $message)
    {
        $this->adapter = $adapter;
        $this->devices = $devices;
        $this->message = $message;
    }

    public function getAdapter():AdapterInterface
    {
        return $this->adapter;
    }

    public function getDevices():DeviceCollection
    {
        return $this->devices;
    }

    public function getMessage():MessageInterface
    {
        return $this->message;
    }
    
    public function push(){
        $this->adapter->push($this->devices, $this->message);
    }
}