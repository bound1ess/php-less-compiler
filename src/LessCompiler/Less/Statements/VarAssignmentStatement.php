<?php namespace LessCompiler\Less\Statements;

/**
 * A LESS variable.
 */
class VarAssignmentStatement extends \LessCompiler\Node {

    /**
     * @param string $name
     * @param string $value
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
