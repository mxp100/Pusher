<?php
/**
 * Created by PhpStorm.
 * User: yuriy
 * Date: 3/17/17
 * Time: 3:38 PM
 */

namespace Pusher\Collection;


use Pusher\Model\Push;

class PushCollection extends AbstractCollection
{
    public function add(Push $push)
    {
        $this->collection[] = $push;
    }
}