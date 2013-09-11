<?php

namespace P;

/**
 * @property \ArrayObject $errors
 */
class InputProcessResult
{

    protected $values = array();
    protected $mappedNames = array();

    protected $errors = null;

    public function __construct()
    {
        $this->errors = new \ArrayObject(array(), \ArrayObject::ARRAY_AS_PROPS);
    }

    public function isValid()
    {
        return ($this->errors->count() == 0);
    }

    public function setValue($name, $value, $mappedName)
    {
        $this->values[$name] = $value;
        $this->mappedNames[$name] = $mappedName;
        return $this;
    }

    public function getMappedValues()
    {
        $return = array();
        foreach ($this->values as $n => $v) {
            $return[$this->mappedNames[$n]] = $v;
        }
        return $return;
    }

    public function getValue($name)
    {
        return $this->values[$name];
    }

    public function getValues()
    {
        return $this->values;
    }

    public function setError($name, $message)
    {
        $this->errors[$name] = $message;
        return $this;
    }

    public function hasErrors()
    {
        return ($this->errors->count() > 0);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getError($name)
    {
        return (isset($this->errors[$name])) ? $this->errors[$name] : null;
    }

    public function __get($name)
    {
        if ($name == 'errors') {
            return $this->errors;
        }
        if (array_key_exists($name, $this->values)) {
            return $this->values[$name];
        }
        return null;
    }

}
