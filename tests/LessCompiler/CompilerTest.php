<?php namespace LessCompiler\Compiler;

class CompilerTest extends \TestCase {

    /**
     * {@inheritdoc}
     */
    protected $sut = "LessCompiler\\Compiler";

    /**
     * @test
     */
    public function it_compiles_AST()
    {
        $ast = \Mockery::mock("LessCompiler\\Less\\LessTree");
        $ast->shouldReceive("getIterator")->once()->andReturn(new \ArrayIterator([]));

        expect($this->sut->compileTree($ast))->to_be_a("LessCompiler\\Css\\CssTree");
    }
}
