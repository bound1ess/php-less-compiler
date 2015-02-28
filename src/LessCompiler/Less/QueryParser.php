<?php namespace LessCompiler\Less;

use LessCompiler\Css\Combinators\Combinator,
    LessCompiler\Css\Selectors\Selector;

/**
 * CSS query parser.
 */
class QueryParser {

    /**
     * @param string $query
     * @return Query
     */
    public function parseQuery($query)
    {
        $query = new Query;
        $elements = $this->split($query);

        foreach ($this->insertCombinators($this->insertSelectors($elements)) as $element) {
            if ($element instanceof Selector) {
                $query->addSelector($element);
            } else if ($element instanceof Combinator) {
                $query->addCombinator($element);
            }
        }

        return $query;
    }

}
