<?php namespace LessCompiler\Css\Printers;

//use LessCompiler\Css\Query,
//    LessCompiler\Css\Container,
//    LessCompiler\Css\CssTree,
//    LessCompiler\Css\Property,
//    LessCompiler\Css\Combinators\ChildCombinator,
//    LessCompiler\Css\Selectors\IdSelector,
//    LessCompiler\Css\Selectors\ClassSelector,
//    LessCompiler\Css\Selectors\AttributeSelector;

class StandardPrinterTest extends \TestCase {

    /**
     * @test
     */
    public function it_prints_an_AST_tree()
    {
        //$query = new Query;

        //$query->addCombinator(new ChildCombinator(
        //    new IdSelector("foo"),
        //    new ClassSelector("fizz")
        //));

        //$query->addSelector(new AttributeSelector("bar", "baz"));

        //$tree = new CssTree([
        //    new Container($query, [
        //        new Property("color", "white"),
        //       new Property("font-size", "18px"),
        //    ]),
        //]);

        //expect((new StandardPrinter)->printTree($tree))
            //->to_include(file_get_contents(__DIR__ . "/../../../printer-output.example"));
    }

}
