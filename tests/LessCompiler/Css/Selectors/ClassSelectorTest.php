<?php namespace LessCompiler\Css\Selectors;

class ClassSelectorTest extends \TestCase {

    /**
     * @test
     */
    public function it_returns_a_string_representation()
    {
        expect((new ClassSelector("foobar"))->represent())->to_be_equal(".foobar");
    }

}
