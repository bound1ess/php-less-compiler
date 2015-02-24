<?php namespace LessCompiler\Css\Selectors;

class IdSelectorTest extends \TestCase {

    /**
     * @test
     */
    public function it_returns_a_string_representation()
    {
        expect((new IdSelector("foobar"))->represent())->to_be_equal_to("#foobar");
    }

}
