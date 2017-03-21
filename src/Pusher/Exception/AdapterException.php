<?php
/**
 * Created by PhpStorm.
 * User: yuriy
 * Date: 3/17/17
 * Time: 4:12 PM
 */

namespace Pusher\Exception;


class AdapterException extends PusherException
{
    const INVALID_RESPONSE = 1;
    const INVALID_RESPONSE_JSON = 2;
    const CAN_NOT_CONNECT = 3;
}