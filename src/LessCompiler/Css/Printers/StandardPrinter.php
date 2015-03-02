<?php namespace LessCompiler\Css\Printers;

use LessCompiler\Css\Query,
    LessCompiler\Css\Property,
    LessCompiler\Css\Container;

/**
 * {@inheritdoc}
 */
class StandardPrinter extends Printer {

    /**
     * {@inheritdoc}
     */
    public function printTree(\LessCompiler\Css\CssTree $tree)
    {
        $printed = [];

        foreach ($tree as $node) {
            if (count($node->getValue("properties")) < 1) {
                continue;
            }

            $printed[] = $this->printContainer($node);
        }

        return implode(PHP_EOL, $printed) . PHP_EOL;
    }

    /**
     * @param \LessCompiler\Css\Container $container
     * @return string
     */
    protected function printContainer(Container $container)
    {
        return sprintf(
            "%s {\n%s\n}",
            $this->printQuery($container->getValue("query")),
            $this->printProperties($container->getValue("properties"))
        );
    }

    /**
     * @param \LessCompiler\Css\Query $query
     * @return string
     */
    protected function printQuery(Query $query)
    {
        return $query->represent();
    }

    /**
     * @param array $properties
     * @return string
     */
    protected function printProperties(array $properties)
    {
        $printed = [];

        foreach ($properties as $property) {
            $printed[] = $this->printProperty($property);
        }

        return implode(PHP_EOL, $printed);
    }

    /**
     * @param \LessCompiler\Css\Property $property
     * @return string
     */
    protected function printProperty(Property $property)
    {
        return sprintf(
            "    %s: %s;",
            $property->getValue("name"),
            $property->getValue("value")
        );
    }

}
