<?php

class TestCase extends Essence\Extensions\PhpunitExtension {

    /**
     * System Under Test.
     *
     * @var string|object
     */
    protected $sut;

    /**
     * @return void
     */
    public function setUp()
    {
        if (is_string($this->sut)) {
            $this->sut = new $this->sut;
        }
    }
}
