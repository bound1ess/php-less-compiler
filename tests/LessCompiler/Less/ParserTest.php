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
    }

}
