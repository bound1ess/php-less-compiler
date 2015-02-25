<?php namespace LessCompiler\Less\Statements;

class ImportStatementTest extends \TestCase {

    /**
     * @test
     */
    public function it_returns_full_file_path()
    {
        expect((new ImportStatement("foo"))->getFullFilePath())->not_to_be_equal_to("foo");
        expect((new ImportStatement("/foo"))->getFullFilePath())->to_be_equal_to("/foo");
    }

    /**
     * @test
     */
    public function it_returns_import_mode()
    {
        expect((new ImportStatement("foo.css"))->getMode())->to_be_equal_to("css");
        expect((new ImportStatement("foo"))->getMode())->to_be_equal_to("less");
        expect((new ImportStatement("foo", "once"))->getMode())->to_be_equal_to("once");
    }

}
