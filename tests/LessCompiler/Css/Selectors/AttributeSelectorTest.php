<?php namespace LessCompiler\Css\Selectors;

class AttributeSelectorTest extends \TestCase {

    /**
     * @test
     */
    public function it_returns_a_string_representation()
    {
        $selector = new AttributeSelector([
            "attribute_name"  => "foobar",
            "attribute_value" => null,
        ]);

        expect($selector->represent())->to_be_equal_to("[foobar]");

        $selector->setValue([
            "attribute_name"  => "foo",
            "attribute_value" => "bar",
        ]);

        expect($selector->represent())->to_be_equal_to("[foo=\"bar\"]");
    }

}
