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

    /**
     * @test
     */
    public function it_finds_a_variable_value_by_its_name()
    {
        $this->sut->setVariable("foo", "bar");

        $scope = new Scope;
        $scope->setParentScope($this->sut);
        $scope->setVariable("baz", "fizz");

        expect($scope->getParentScope())->to_be_equal_to($this->sut);

        expect($scope->resolve("baz"))->to_be_equal_to("fizz");
        expect($scope->resolve("foo"))->to_be_equal_to("bar");
        expect(function() use ($scope) {
            $scope->resolve("invalid");
        })->to_throw("LessCompiler\\Compiler\\Exceptions\\UndefinedVariableException");
    }

    /**
     * @test
     */
    public function it_inserts_variable_value_into_a_string()
    {
        $this->sut->setVariable("foo", "bar");
        $this->sut->setVariable("baz", "fizz");

        expect($this->sut->interpolate("what is @foo?"))->to_be_equal_to("what is bar?");
        expect($this->sut->interpolate("what is @{baz}?"))->to_be_equal_to("what is fizz?");
    }

}
