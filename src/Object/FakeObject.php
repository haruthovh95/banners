<?php
namespace Zakhayko\Banners\Object;

class FakeObject implements \ArrayAccess, \Countable, \IteratorAggregate
{
    public function offsetExists($offset)
    {
        return false;
    }

    public function offsetGet($offset)
    {
        return $this;
    }

    public function getIterator()
    {
        return new \ArrayIterator([]);
    }

    public function offsetSet($offset, $value) {return false;}

    public function offsetUnset($offset){return false;}

    public function count() {
        return 0;
    }

    public function __get($argument){
        return $this;
    }
    
    public function __call($method, $arguments){
        return $this;
    }

    public function __toString()
    {
        return '';
    }
}
