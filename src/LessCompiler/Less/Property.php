<?php namespace LessCompiler\Less;

/**
 * A LESS property.
 */
class Property extends \LessCompiler\Node {

    /**
     * @param string $name
     * @param string $value
     * @return Property
     */
    public function __construct($name, $value)
    {
        parent::__construct(compact ("name", "value"));
    }

}
