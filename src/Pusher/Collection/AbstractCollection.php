<?php
/**
 * Created by PhpStorm.
 * User: yuriy
 * Date: 3/17/17
 * Time: 2:01 PM
 */

namespace Pusher\Collection;

abstract class AbstractCollection implements \IteratorAggregate
{
    /** @var \ArrayIterator */
    protected $collection;

    public function __construct(array $collection = [])
    {
        $this->collection = new \ArrayIterator($collection);
    }

    public function getIterator()
    {
        return $this->collection;
    }

    public function get($key)
    {
        return isset($this->collection[$key]) ? $this->collection[$key] : null;
    }

    public function count()
    {
        return count($this->getIterator());
    }

    public function isEmpty()
    {
        return $this->count() === 0;
    }

    public function clear()
    {
        $this->collection = new \ArrayIterator();
    }

}