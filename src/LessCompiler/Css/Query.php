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
            "elements" => [],
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
                // If that's an AttributeSelector, we don't want an extra space character.
                if (preg_match("/^\[(.+)\]$/", $returnedValue = $element->represent())) {
                    $representation .= $returnedValue;
                } else {
                    $representation .= sprintf("%s ", $returnedValue);
                }
            } else {
                // That must be a Combinator.
                $representation .= $element->combine();
            }
        }

        return $representation;
    }

}
