<?php
/**
 * Created by PhpStorm.
 * User: yuriy
 * Date: 3/17/17
 * Time: 1:54 PM
 */

namespace Pusher;


use Pusher\Collection\PushCollection;
use Pusher\Exception\AdapterException;
use Pusher\Model\Push;

class Pusher extends PushCollection
{
    protected $skipError = false;

    /**
     * Send messages to devices
     */
    public function push()
    {
        $result = [];

        foreach ($this as $push) {
            /** @var Push $push */

            try {
                $results = $push->push();
                if ($results) {
                    array_push($result, ...$results);
                }
            } catch (AdapterException $e){
                if (!$this->skipError)
                    throw $e;
            }
        }

        return $result;
    }

    public function skipError($state)
    {
        $this->skipError = $state;
    }
}
