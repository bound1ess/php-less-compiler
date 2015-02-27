<?php namespace LessCompiler\Less;

class ParserTest extends \TestCase {

    /**
     * {@inheritdoc}
     */
    protected $sut = "LessCompiler\\Less\\Parser";

    /**
     * @test
     */
    public function it_skips_comments()
    {
        expect($this->sut->parse("// whatever")->getNodes())->to_have_length(0);
        expect($this->sut->parse("/* cool */")->getNodes())->to_have_length(0);

        // This one is much more complicated.
        expect($this->sut->parse("/*" . PHP_EOL . "* whatever" . PHP_EOL . "*/")->getNodes())
            ->to_have_length(0);
    }

    /**
     * @test
     */
    public function it_parses_an_import_statement()
    {
        $tree = $this->sut->parse("@import (css) 'something.css' // comment");

        expect($tree->getNodes())->to_have_length(1);
        expect($node = $tree->getFirstNode())
            ->to_be_a("LessCompiler\\Less\\Statements\\ImportStatement");

        expect($node->getFullFilePath())->to_be_equal_to(getcwd() . "/something.css");
        expect($node->getMode())->to_be_equal_to("css");

        // 2nd case:
        $tree = $this->sut->parse("@import /* hey */ \"main.less\"");

        expect($tree->getNodes())->to_have_length(1);
    }

    /**
     * @test
     */
    public function it_parses_a_variable_assignment()
    {
        $tree = $this->sut->parse("    @foo:  123  ;");

        expect($node = $tree->getFirstNode())
            ->to_be_a("LessCompiler\\Less\\Statements\\VarAssignmentStatement");

        expect($node->getValue("name"))->to_be_equal_to("foo");
        expect($node->getValue("value"))->to_be_equal_to("123");
    }

    /**
     * @test
     */
    public function it_parses_a_rule()
    {
        $tree = $this->sut->parse(
            "#container > .item {" . PHP_EOL . "foo: bar;  "
            . PHP_EOL . "baz: fizz; " . PHP_EOL . "}"
        );

        expect($properties = $tree->getFirstNode()->getValue("properties"))
            ->to_have_length(2);

        expect($properties[0]->getValue("name"))->to_be_equal_to("foo");
        expect($properties[1]->getValue("value"))->to_be_equal_to("fizz");
    }

    /**
     * @test
     */
    public function it_parses_nested_rules()
    {
        $tree = $this->sut->parse(
            ".selector {" . PHP_EOL . ".another-selector {" . PHP_EOL . "}" . PHP_EOL . "}"
        );

        expect($tree->getNodes())->to_have_length(1);
        expect($tree->getFirstNode()->getValue("children"))->to_have_length(1);
    }

}
