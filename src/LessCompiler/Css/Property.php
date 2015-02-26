<?php namespace LessCompiler\Css;

/**
 * A CSS property.
 */
class Property extends \LessCompiler\Property {

    /**
     * @param string $name
     * @param string $value
     * @return Property
     */
    public function __construct($name, $value)
    {
        parent::__construct([
            "name"  => $name,
            "value" => $value,
        ]);
    }

}
