<?php namespace LessCompiler\Css\Selectors;

class PseudoSelectorTest extends \TestCase {

    /**
     * @test
     */
    public function it_returns_a_string_representation()
    {
        expect((new PseudoSelector("foobar"))->represent())->to_be_equal_to(":foobar");
    }

}
