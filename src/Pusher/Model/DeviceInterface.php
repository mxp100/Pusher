<?php
/**
 * Created by PhpStorm.
 * User: yuriy
 * Date: 3/17/17
 * Time: 2:16 PM
 */

namespace Pusher\Model;


interface DeviceInterface
{
    public function __construct(string $token, array $parameters);

    public function getParameters():array;

    public function getToken():string;

    public function setToken(string $token);
}