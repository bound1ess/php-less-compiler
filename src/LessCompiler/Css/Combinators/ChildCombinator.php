<?php namespace LessCompiler\Css\Combinators;

/**
 * Unlike Descendant, ChildCombinator only targets immediate child elements.
 */
class ChildCombinator extends Combinator {

    /**
     * {@inheritdoc}
     */
    public function represent()
    {
        return " > ";
    }

}
