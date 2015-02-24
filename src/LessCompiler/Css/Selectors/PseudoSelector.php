<?php namespace LessCompiler\Css\Selectors;

/**
 * This pseudo-selector is pretty pseudo.
 */
class PseudoSelector extends Selector {

    /**
     * {@inheritdoc}
     */
    public function represent()
    {
        return sprintf(":%s", $this->value);
    }

}
