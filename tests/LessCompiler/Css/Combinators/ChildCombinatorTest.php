<?php namespace LessCompiler\Css\Combinators;

class ChildCombinatorTest extends \TestCase {

    /**
     * @test
     */
    public function it_combines_two_selectors()
    {
        $combinator = new ChildCombinator(
            $this->mockSelector("#form"),
            $this->mockSelector("input")
        );

        expect($combinator->combine())->to_be_equal("#form > input");
    }

}
