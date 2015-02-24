<?php namespace LessCompiler\Css;

class ContainerTest extends \TestCase {

    /**
     * @test
     */
    public function it_stores_properties()
    {
        $query     = \Mockery::mock("LessCompiler\\Css\\Query");
        $container = new Container($query);

        $container->addProperties([
            $this->mockProperty(),
            $this->mockProperty(),
        ]);

        $container->addProperty($this->mockProperty());

        expect($container->getValue("properties"))->to_have_length(3);
        expect($container->getValue())->keys->to_include("query");
    }

    /**
     * @return object
     */
    protected function mockProperty()
    {
        return \Mockery::mock("LessCompiler\\Css\\Property");
    }

}
