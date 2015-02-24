<?php namespace LessCompiler\Css;

class PropertyTest extends \TestCase {

    /**
     * @test
     */
    public function it_stores_property_name_and_value()
    {
        expect((new Property("color", "black"))->getValue())
            ->to_be_equal_to([
                "name"  => "color",
                "value" => "black",
            ]);
    }

}
