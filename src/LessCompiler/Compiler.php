<?php namespace LessCompiler;

/**
 * LESS compiler.
 */
class Compiler {

    /**
     * @param \LessCompiler\Less\LessTree $tree
     * @return \LessCompiler\Css\CssTree
     */
    public function compileTree(Less\LessTree $tree)
    {
        $newTree = new Css\CssTree;

        // Init global scope and ScopeManager.
        $globalScope = new Compiler\Scope;
        $scopeManager = new Compiler\ScopeManager;

        foreach ($tree as $node) {
            if ($node instanceof Less\Statements\ImportStatement) {
                // ...
            }

            if ($node instanceof Less\Statements\VarAssignmentStatement) {
                $globalScope->setVariable(
                    $node->getValue("name"),
                    $node->getValue("value")
                );

                continue;
            }
        }

        return $newTree;
    }
}
