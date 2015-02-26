<?php namespace LessCompiler\Less;

/**
 * A LESS property.
 */
class Property extends \LessCompiler\Property {

    /**
     * @param string $name
     * @param string $value
     * @return Property
     */
    public function __construct($name, $value)
    {
        $this->value = compact ("name", "value");
    }

}
