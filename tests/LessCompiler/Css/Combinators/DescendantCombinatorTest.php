<?php namespace LessCompiler\Css\Combinators;

class DescendantCombinatorTest extends \TestCase {

    /**
     * @test
     */
    public function it_combines_two_selectors()
    {
        $combinator = new DescendantCombinator(
            $this->mockSelector("#list"),
            $this->mockSelector(".element")
        );

        expect($combinator->combine())->to_be_equal("#list .element");
    }

}
