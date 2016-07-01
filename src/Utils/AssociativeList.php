<?php

namespace BreakDown\Core\Meta\Utils;

use ArrayAccess;
use ArrayIterator;
use Countable;
use Exception;
use IteratorAggregate;

class AssociativeList implements ArrayAccess, Countable, IteratorAggregate
{

    /**
     *
     * @var array
     */
    protected $data = null;

    public function __construct(array $data = [])
    {
        if (!$data) {
            $data = [];
        }
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function offsetSet($offset, $value)
    {
        if (!is_string($offset)) {
            throw new Exception("Api response should only have associative keys. Numeric keys are not allowed.");
        }

        $this->data[$offset] = $value;
    }

    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)) {
            return null;
        }

        return $this->data[$offset];
    }

    public function count()
    {
        return count($this->data);
    }

    public function getIterator() {
        return new ArrayIterator($this->data);
    }

    public function __get($offset)
    {
        return $this[$offset];
    }

    public function __set($offset, $value)
    {
        $this[$offset] = $value;
    }

}
