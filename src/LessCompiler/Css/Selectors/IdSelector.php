<?php namespace LessCompiler\Css\Selectors;

/**
 * This selector selects an element with a specific ID.
 */
class IdSelector extends Selector {

    /**
     * {@inheritdoc}
     */
    public function represent()
    {
        return sprintf("#%s", $this->value);
    }

}
