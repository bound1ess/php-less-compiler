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
        return new Css\CssTree;
    }
}
