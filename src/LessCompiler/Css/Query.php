<?php namespace LessCompiler\Css;

/**
 * A query string. Consists of Selectors and Combinators.
 */
class Query extends \LessCompiler\Node {

    /**
     * @return Query
     */
    public function __construct()
    {
        parent::__construct([
            "combinators" => [],
            "selectors"   => [],
        ]);
    }

    /**
     * @param \LessCompiler\Css\Selectors\Selector $selector
     * @return void
     */
    public function addSelector(Selectors\Selector $selector)
    {
        $this->value["selectors"][] = $selector;
    }

    /**
     * @param \LessCompiler\Css\Combinators\Combinator $combinator
     * @return void
     */
    public function addCombinator(Combinators\Combinator $combinator)
    {
        $this->value["combinators"][] = $combinator;
    }

    /**
     * @return string
     */
    public function represent()
    {
        // ...
    }

}
