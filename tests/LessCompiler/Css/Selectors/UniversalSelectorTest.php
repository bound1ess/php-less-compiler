<?php namespace LessCompiler\Css\Selectors;

class UniversalSelectorTest extends \TestCase {

    /**
     * {@inheritdoc}
     */
    protected $sut = "LessCompiler\\Css\\Selectors\\UniversalSelector";

    /**
     * @test
     */
    public function it_returns_a_string_representation()
    {
        expect($this->sut->represent())->to_be_equal("*");
    }

}
