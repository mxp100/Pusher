<?php
/**
 * Created by PhpStorm.
 * User: yuriy
 * Date: 3/17/17
 * Time: 3:38 PM
 */

namespace Pusher\Collection;


use Pusher\Model\Push;

/**
 * Class PushCollection
 *
 * @package Pusher\Collection
 */
class PushCollection extends AbstractCollection
{
    /**
     * Add push
     *
     * @param \Pusher\Model\Push $push
     */
    public function add(Push $push)
    {
        $this->collection[] = $push;
    }
}