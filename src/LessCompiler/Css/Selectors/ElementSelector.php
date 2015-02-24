<?php namespace LessCompiler\Css\Selectors;

/**
 * This selector selects all elements of equal type (tag name).
 */
class ElementSelector extends Selector {

    /**
     * {@inheritdoc}
     */
    public function represent()
    {
        return $this->value;
    }

}
