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
        $dumpy = new \PhpPackages\Dumpy\Dumpy;

        $dumped = [];

        foreach ($tree as $node) {
            $dumped[] = $this->dumpValue($node->getValue(), $node);
        }

        return $dumpy->dump($dumped);
    }

    /**
     * @param mixed $value
     * @param object $object
     * @return array
     */
    protected function dumpValue($value, $object)
    {
        $dumped = [];

        if ( ! is_array($value)) {
            return $value;
        }

        foreach ($value as $key => $anotherValue) {
            if ( ! is_integer($key)) {
                $key = sprintf(
                    "%s: %s",
                    (new \ReflectionClass($object))->getShortName(),
                    $key
                );
            }

            if (is_array($anotherValue)) {
                $dumped[$key] = $this->dumpValue($anotherValue, $object);
            } else if ($anotherValue instanceof Node) {
                $dumped[$key] = $this->dumpValue($anotherValue->getValue(), $anotherValue);
            } else {
                $dumped[$key] = $anotherValue;
            }
        }

        return $dumped;
    }

}
