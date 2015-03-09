<?php namespace LessCompiler\Less;

/**
 * A CSS query string (selector).
 */
class Query extends \LessCompiler\Css\Query {

    /**
     * @param Query $query
     * @return Query
     */
    public function merge(Query $query)
    {
        return new Query(array_merge(
            $this->value["elements"],
            $query->getValue("elements")
        ));
    }

}
