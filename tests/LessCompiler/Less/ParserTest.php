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

}
