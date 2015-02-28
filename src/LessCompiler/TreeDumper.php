<?php namespace LessCompiler;

/**
 * An AST dumper.
 */
class TreeDumper {

    /**
     * @param \LessCompiler\AbstractSyntaxTree $tree
     * @return string
     */
    public function dumpTree(AbstractSyntaxTree $tree)
    {
        $output = "";

        foreach ($tree as $node) {
            $output .= $this->dumpNode($node);
        }

        return $output;
    }

    /**
     * @param \LessCompiler\Node $node
     * @return string
     */
    protected function dumpNode(Node $node, $indenting = 0)
    {
        $indentation = "  ";

        $output = sprintf(
            "%s%s:%s",
            str_repeat($indentation, $indenting + 1),
            (new \ReflectionClass($node))->getShortName(),
            PHP_EOL
        );

        if (is_array($node->getValue())) {
            foreach ($node->getValue() as $key => $value) {
                if ($value instanceof Node) {
                    $value = $this->dumpNode($value, $indenting + 1);
                }

                // Assuming it's not nested.
                if (is_array($value)) {
                    foreach ($value as $anotherValue) {
                        $output .= $this->dumpNode($anotherValue);
                    }

                    continue;
                }

                $output .= sprintf(
                    "%s%s: %s%s",
                    str_repeat($indentation, $indenting + 2),
                    ucfirst($key),
                    $value,
                    PHP_EOL
                );
            }
        } else {
            $output = sprintf("%sValue: %s", $indentation, $node->getValue());
        }

        return $output;
    }

    /**
     * @param mixed $value
     * @return string
     */
    protected function dumpValue($value)
    {
        // ...
    }

}
