<?php namespace LessCompiler\Css\Selectors;

/**
 * A CSS selector.
 */
abstract class Selector extends \LessCompiler\Node {

    /**
     * @return string
     */
    public abstract function represent();

}
