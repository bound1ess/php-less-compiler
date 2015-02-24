<?php namespace LessCompiler\Css\Combinators;

/**
 * This combinator matches elements based on their sibling relationships.
 */
class GeneralSiblingCombinator extends Combinator {

    /**
     * {@inheritdoc}
     */
    public function represent()
    {
        return " ~ ";
    }

}
