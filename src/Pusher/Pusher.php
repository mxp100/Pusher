<?php
/**
 * Created by PhpStorm.
 * User: yuriy
 * Date: 3/17/17
 * Time: 1:54 PM
 */

namespace Pusher;


use Pusher\Collection\PushCollection;
use Pusher\Model\Push;

class Pusher extends PushCollection
{
    /**
     * Send messages to devices
     */
    public function push()
    {
        $result = [];

        foreach ($this as $push) {
            /** @var Push $push */

            if($results = $push->push()) {
                array_push($result, ...$results);
            }
        }

        return $result;
    }
}