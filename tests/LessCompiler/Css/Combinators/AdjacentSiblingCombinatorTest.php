<?php namespace LessCompiler\Css\Combinators;

class AdjacentSiblingCombinatorTest extends \TestCase {

    /**
     * @test
     */
    public function it_combines_two_selectors()
    {
        $combinator = new AdjacentSiblingCombinator(
            $this->mockSelector(".menu"),
            $this->mockSelector("li")
        );

        expect($combinator->combine())->to_be_equal_to(".menu + li");
    }

}
