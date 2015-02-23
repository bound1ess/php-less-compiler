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
        expect($this->sut)->to_respond_to("compileTree");
    }
}
