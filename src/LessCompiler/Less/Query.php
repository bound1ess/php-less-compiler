<?php namespace LessCompiler\Less;

/**
 * A CSS query string.
 */
class Query extends \LessCompiler\Css\Query {

    /**
     * @param Query $query
     * @return Query
     */
    public function merge(Query $query)
    {
        $this->value["elements"] = array_merge(
            $this->value["elements"],
            $query->getValue("elements")
        );

        return $this;
    }

}
