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
        $ast = \Mockery::mock("LessCompiler\\AbstractSyntaxTree");

        expect($this->sut->compileTree($ast))->to_be_of_type("string");
    }
}
