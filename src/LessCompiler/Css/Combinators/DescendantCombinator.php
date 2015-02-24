<?php namespace LessCompiler\Css\Combinators;

/**
 * This combinator allows you to make the selection method more specific.
 */
class DescendantCombinator extends Combinator {

    /**
     * {@inheritdoc}
     */
    public function represent()
    {
        return " ";
    }

}
