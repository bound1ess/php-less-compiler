<?php namespace LessCompiler\Css\Selectors;

class AttributeSelectorTest extends \TestCase {

    /**
     * @test
     */
    public function it_returns_a_string_representation()
    {
        expect((new AttributeSelector("foobar"))->represent())->to_be_equal_to("[foobar]");

        expect((new AttributeSelector("foo", "bar"))->represent())
            ->to_be_equal_to("[foo=\"bar\"]");
    }

}
