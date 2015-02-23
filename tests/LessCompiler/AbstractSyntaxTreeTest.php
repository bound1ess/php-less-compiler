<?php namespace LessCompiler;

class AbstractSyntaxTreeTest extends \TestCase {

    /**
     * {@inheritdoc}
     */
    protected $sut = "LessCompiler\\AbstractSyntaxTree";

    /**
     * @test
     */
    public function it_manages_nodes()
    {
        expect($this->sut->getNodes())->to_have_length(0);

        $this->sut->addNodes([$this->createNodeMock(), $this->createNodeMock()]);

        expect($this->sut->getNodes())->to_have_length(2);

        $this->sut->addNode($this->createNodeMock());

        expect($this->sut->getNodes())->to_have_length(3);
    }

    /**
     * @test
     */
    public function it_is_traversable()
    {
        expect($this->sut instanceof \Traversable)->to_be_true();
        expect($this->sut->getIterator())->to_be_an("ArrayIterator");
    }

    /**
     * @return object
     */
    protected function createNodeMock()
    {
        return \Mockery::mock("LessCompiler\\Node");
    }

}
