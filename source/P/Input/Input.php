<?php

namespace P\Input;

class Input implements \ArrayAccess
{
    const BAD_INPUT = '__BAD_INPUT__';
    protected $specifications = array();

    public function __construct(array $specifications = array())
    {
        if ($specifications) {
            $this->setSpecifications($specifications);
        }
    }

    public function setSpecifications(array $specifications)
    {
        $this->specifications = $specifications;
        return $this;
    }

    public function process(array $source)
    {
        $result = new ProcessResult();
        foreach ($this->specifications as $name => $specification) {

            $mapName = (isset($specification['name'])) ? $specification['name'] : $name;
            $sourceValue = (isset($source[$name])) ? $source[$name] : null;

            $input = $specification['process']($source, $sourceValue);
            $result->setValue($name, $input, $mapName);
            if ($input === self::BAD_INPUT) {
                $result->setError($name, $specification['error']);
            }
        }
        return $result;
    }

    public function offsetExists($name)
    {
        return isset($this->specifications[$name]);
    }

    public function offsetGet($name)
    {
        return $this->specifications[$name];
    }

    public function offsetSet($name, $specification)
    {
        $this->specifications[$name] = $specification;
    }

    public function offsetUnset($name)
    {
        unset($this->specifications[$name]);
    }
}