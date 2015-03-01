<?php namespace LessCompiler;

/**
 * LESS compiler.
 */
class Compiler {

    /**
     * @var ScopeManager
     */
    protected $scopeManager;

    /**
     * @param Less\LessTree $tree
     * @return Css\CssTree
     */
    public function compileTree(Less\LessTree $tree)
    {
        $newTree = new Css\CssTree;

        // Init ScopeManager.
        $this->scopeManager = new Compiler\ScopeManager;

        foreach ($tree as $node) {
            if ($node instanceof Less\Statements\ImportStatement) {
                // ...
                continue;
            }

            if ($node instanceof Less\Statements\VarAssignmentStatement) {
                $this->scopeManager->getScope("global")->setVariable(
                    $node->getValue("name"),
                    $node->getValue("value")
                );

                continue;
            }

            if ($node instanceof Less\Container) {
                foreach ($this->compileContainer($node) as $container) {
                    $newTree->addNode($container);
                }
            }
        }

        return $newTree;
    }

    /**
     * @param Less\Container $container
     * @param Less\Query $query
     * @return Css\Container
     */
    protected function compileContainer(Less\Container $container, Less\Query $query = null)
    {
        $containers = [];

        // Build proper selector value.
        if ( ! is_null($query)) {
            $id = $query->merge($container->getValue("query"))->represent();
        } else {
            $id = $container->getValue("query")->represent();
        }

        // ...and scope.
        $scope = $this->scopeManager->getScope(
            $id,
            $this->scopeManager->getScope($id)
        );

        // Set variables...
        foreach ($container->getValue("variables") as $variable) {
            $scope->setVariable(
                $variable->getValue("name"),
                $variable->getValue("value")
            );
        }

        // Set properties.
        $newContainer = new Css\Container(new Css\Query);

        foreach ($container->getValue("properties") as $property) {
            $newContainer->addProperty(new Css\Property(
                $property->getValue("name"),
                $scope->interpolate($property->getValue("value"))
            ));
        }

        $containers[] = $newContainer;

        // Nested.
        foreach ($container->getValue("children") as $children) {
            $containers = array_merge(
                $containers,
                $this->compileContainer($children, $container->getValue("query"))
            );
        }

        return $containers;
    }
}
