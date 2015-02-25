<?php namespace LessCompiler\Css\Printers;

/**
 * Pretty CSS printer.
 */
abstract class Printer {

    /**
     * @param \LessCompiler\Css\CssTree $tree
     * @return string
     */
    public abstract function printTree(\LessCompiler\Css\CssTree $tree);

}
