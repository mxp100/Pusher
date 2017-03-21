<?php
/**
 * Created by PhpStorm.
 * User: yuriy
 * Date: 3/17/17
 * Time: 2:00 PM
 */

namespace Pusher\Model;


class Device implements DeviceInterface
{
    protected $token;
    protected $parameters;

    public function __construct(string $token, array $parameters = [])
    {
        $this->token = $token;
        $this->parameters = $parameters;
    }

    public function getParameters():array
    {
        return $this->parameters;
    }

    public function getToken():string 
    {
        return $this->token;
    }

    public function setToken(string $token)
    {
        $this->token = $token;
    }
}