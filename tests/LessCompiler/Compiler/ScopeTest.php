<?php namespace LessCompiler\Compiler;

class ScopeTest extends \TestCase {

    /**
     * {@inheritdoc}
     */
    protected $sut = "LessCompiler\\Compiler\\Scope";

    /**
     * @test
     */
    public function it_checks_whether_it_has_a_parent_scope()
    {
        expect($this->sut->isMain())->to_be_true;

        $this->sut->setParentScope(new Scope);
        expect($this->sut->isMain())->to_be_false;
    }

}
