<?php namespace LessCompiler\Css;

/**
 * A container that contains:
 *    1) a query (selector) string
 *    2) a set of properties
 */
class Container extends \LessCompiler\Node {

    /**
     * @param \LessCompiler\Css\Query $query
     * @param array $properties
     * @return Container
     */
    public function __construct(Query $query, array $properties = [])
    {
        parent::__construct([
            "query"      => $query,
            "properties" => [],
        ]);

        $this->addProperties($properties);
    }

    /**
     * @param array $properties
     * @return void
     */
    public function addProperties(array $properties)
    {
        foreach ($properties as $property) {
            $this->addProperty($property);
        }
    }

    /**
     * @param \LessCompiler\Property $property
     * @return void
     */
    public function addProperty(\LessCompiler\Property $property)
    {
        $this->value["properties"][] = $property;
    }

}
