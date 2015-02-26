<?php namespace LessCompiler\Less\Statements;

/**
 * A LESS variable.
 */
class VarAssignmentStatement \LessCompiler\Node {

    /**
     * @param string $name
     * @param mixed $value
     * @return VarAssignmentStatement
     */
    public function __construct($name, $value)
    {
        parent::__construct([
            "name"  => $name,
            "value" => $value,
        ]);
    }

}
