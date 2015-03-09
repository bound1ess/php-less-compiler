<?php namespace LessCompiler\Css;

use LessCompiler\Compiler\Scope,
    PhpLessCompiler\SelectorParser\Printer;

/**
 * A query string.
 */
class Query extends \LessCompiler\Node {

    /**
     * @param array $elements
     * @return Query
     */
    public function __construct(array $elements = [])
    {
        parent::__construct([
            "elements" => $elements,
            "scope"    => null,
        ]);
    }

    /**
     * @return string
     */
    public function represent()
    {
        $output = (new Printer)->_print($this->value["elements"]);

        return ! is_null($this->value["scope"]) ?
            $this->value["scope"]->interpolate($output) : $output;
    }

    /**
     * @param Scope $scope
     * @return void
     */
    public function attachScope(Scope $scope)
    {
        $this->value["scope"] = $scope;
    }
}
