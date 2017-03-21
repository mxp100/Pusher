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
    public function push()
    {
        foreach ($this as $push) {
            /** @var Push $push */

            $push->push();
        }
    }
}