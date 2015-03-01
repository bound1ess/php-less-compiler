<?php namespace LessCompiler\Css;

/**
 * A query string. Consists of Selectors and Combinators.
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
        ]);
    }

    /**
     * @param \LessCompiler\Css\Selectors\Selector $selector
     * @return void
     */
    public function addSelector(Selectors\Selector $selector)
    {
        $this->value["elements"][] = $selector;
    }

    /**
     * @param \LessCompiler\Css\Combinators\Combinator $combinator
     * @return void
     */
    public function addCombinator(Combinators\Combinator $combinator)
    {
        $this->value["elements"][] = $combinator;
    }

    /**
     * @return string
     */
    public function represent()
    {
        $representation = "";

        foreach ($this->value["elements"] as $element) {
            // If that's a Selector, call represent().
            if ($element instanceof Selectors\Selector) {
                $representation .= $element->represent();
            } else {
                // That must be a Combinator.
                $representation .= $element->combine();
            }
        }

        return $representation;
    }

}
