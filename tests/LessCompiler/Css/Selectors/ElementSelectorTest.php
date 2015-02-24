<?php namespace LessCompiler\Css\Selectors;

class ElementSelectorTest extends \TestCase {

    /**
     * @test
     */
    public function it_returns_a_string_representation()
    {
        expect((new ElementSelector("foobar"))->represent())->to_be_equal_to("foobar");
    }

}
