<?php namespace LessCompiler\Less;

/**
 * A CSS query string.
 */
class Query extends \LessCompiler\Css\Query {

    /**
     * @param \LessCompiler\Less\Query $query
     * @return void
     */
    public function merge(Query $query)
    {
        $this->value = array_merge($this->value, $query->getValue());
    }

}
