<?php namespace LessCompiler\Css\Combinators;

/**
 * Unlike GeneralSiblingCombinator, an element must be an immediate sibling.
 */
class AdjacentSiblingCombinator extends Combinator {

    /**
     * {@inheritdoc}
     */
    public function represent()
    {
        return " + ";
    }

}
