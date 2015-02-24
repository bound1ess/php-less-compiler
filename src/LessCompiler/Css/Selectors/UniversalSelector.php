<?php namespace LessCompiler\Css\Selectors;

/**
 * This selector affects ALL elements on a page.
 */
class UniversalSelector extends Selector {

    /**
     * {@inheritdoc}
     */
    public function represent()
    {
        return "*";
    }

}
