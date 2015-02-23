<?php namespace LessCompiler;

class Node {

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param mixed $value
     * @return Node
     */
    public function __construct($value = null)
    {
        $this->setValue($value);
    }

    /**
     * @param mixed $value
     * @return void
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @param string|integer|null $key
     * @return mixed
     */
    public function getValue($key = null)
    {
        if ( ! is_null($key)) {
            return $this->value[$key];
        }

        return $this->value;
    }

}
