<?php namespace LessCompiler\Css;

class QueryTest extends \TestCase {

    /**
     * {@inheritdoc}
     */
    protected $sut = "LessCompiler\\Css\\Query";

    /**
     * @test
     */
    public function it_builds_a_query_string()
    {
        //$this->sut->addSelector($this->mockSelector("#container"));
        //$this->sut->addCombinator($this->mockCombinator(".element > .sub-element"));
        //$this->sut->addSelector($this->mockSelector("[type=\"text\"]"));

        //expect($this->sut->represent())
        //    ->to_be_equal_to("#container .element > .sub-element [type=\"text\"]");
    }

    /**
     * @param string $representation
     * @return object
     */
    protected function mockCombinator($representation)
    {
        $combinator = \Mockery::mock("LessCompiler\\Css\\Combinators\\Combinator");

        $combinator->shouldReceive("combine")->once()->andReturn($representation);

        return $combinator;
    }

}
