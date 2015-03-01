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
     * @param \LessCompiler\Less\LessTree $tree
     * @return \LessCompiler\Css\CssTree
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
     * @param string $id
     * @return Css\Container
     */
    protected function compileContainer(Less\Container $container, $id = "")
    {
        $containers = [];

        $id = implode(" ", [$id, $container->getValue("query")->represent()]);
        var_dump($id);

        // Nested.
        foreach ($container->getValue("children") as $children) {
            $containers = array_merge($containers, $this->compileContainer($children, $id));
        }

        return $containers;
    }
}
