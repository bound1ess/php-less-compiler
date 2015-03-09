<?php namespace LessCompiler\Less;

/**
 * CSS query parser (wrapper).
 */
class QueryParser extends \PhpLessCompiler\SelectorParser\Parser {

    /**
     * @param string $query
     * @return Query
     */
    public function parseQuery($query)
    {
        return new Query($this->parse($query));
    }
}
