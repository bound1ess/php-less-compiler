<?php namespace LessCompiler\Css\Combinators;

use LessCompiler\Css\Selectors\Selector;

/**
 * Combinator combines two Selectors.
 */
abstract class Combinator extends \LessCompiler\Node {

    /**
     * @return string
     */
    abstract public function represent();

    /**
     * @param \LessCompiler\Css\Selectors\Selector $one
     * @param \LessCompiler\Css\Selectors\Selector $another
     * @return Combinator
     */
    public function __construct(Selector $one, Selector $another)
    {
        parent::__construct([
            "one"     => $one,
            "another" => $another,
        ]);
    }

    /**
     * @return string
     */
    public function combine()
    {
        return sprintf(
            "%s%s%s",
            $this->value["one"]->represent(),
            $this->represent(),
            $this->value["another"]->represent()
        );
    }

}
