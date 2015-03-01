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

    /**
     * @test
     */
    public function it_adds_a_new_variable()
    {
        $this->sut->setVariable("foo", "bar");
        $sut =& $this->sut;

        expect($this->sut->resolve("foo"))->to_be_equal_to("bar");
        expect(function() use($sut) {
            $sut->setVariable("foo", "fizz");
        })->to_throw("LessCompiler\\Compiler\\Exceptions\\VarAlreadyDefinedException");
    }

}
