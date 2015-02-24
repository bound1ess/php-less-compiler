<?php namespace LessCompiler\Css\Combinators;

class GeneralSiblingCombinatorTest extends \TestCase {

    /**
     * @test
     */
    public function it_combines_two_selectors()
    {
        $combinator = new GeneralSiblingCombinator(
            $this->mockSelector("div"),
            $this->mockSelector(".item")
        );

        expect($combinator->combine())->to_be_equal_to("div ~ .item");
    }

}
