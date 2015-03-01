<?php namespace LessCompiler\Less;

/**
 * A container that stores a query (selector) string, a bunch of Property instances
 * and (probably) some child Container instances.
 */
class Container extends \LessCompiler\Css\Container {

    /**
     * @param \LessCompiler\Less\Query $query
     * @param array $properties
     * @param array $children
     * @return Container
     */
    public function __construct(Query $query, array $properties = [], array $children = [])
    {
        $this->value = compact ("query", "properties", "children");
        $this->value["variables"] = [];
    }

    /**
     * @param \LessCompiler\Less\Container $container
     * @return void
     */
    public function addChildContainer(Container $container)
    {
        $this->value["children"][] = $container;
    }

    /**
     * @param \LessCompiler\Less\Statements\VarAssignmentStatement $var
     * @return void
     */
    public function addVariable(Statements\VarAssignmentStatement $var)
    {
        $this->value["variables"][] = $var;
    }

}
