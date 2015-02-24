<?php namespace LessCompiler\Css\Selectors;

/**
 * This selector selects elements with a specific class.
 */
class ClassSelector extends Selector {

    /**
     * {@inheritdoc}
     */
    public function represent()
    {
        return sprintf(".%s", $this->value);
    }

}
