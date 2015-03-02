<?php namespace LessCompiler\Css;

use LessCompiler\Compiler\Scope;

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
        $representation = [];

        foreach ($this->value["elements"] as $element) {
            // If that's a Selector, call represent().
            if ($element instanceof Selectors\Selector) {
                // If that's an AttributeSelector, append to the last element.
                if ($element instanceof Selectors\AttributeSelector) {
                    $representation[count($representation) - 1] .= $element->represent();

                    continue;
                }

                $representation[] = $element->represent();
            } else {
                // That must be a Combinator.
                $representation[] = $element->combine();
            }
        }

        $output = implode(" ", $representation);

        return isset ($this->value["scope"]) ?
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
