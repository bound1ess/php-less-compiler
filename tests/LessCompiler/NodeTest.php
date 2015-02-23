<?php namespace LessCompiler;

class NodeTest extends \TestCase {

    /**
     * {@inheritdoc}
     */
    protected $sut = "LessCompiler\\Node";

    /**
     * @test
     */
    public function it_stores_a_value()
    {
        expect($this->sut->getValue())->to_be_null();

        $this->sut->setValue("foobar");

        expect($this->sut->getValue())->to_be_equal_to("foobar");
    }

}
