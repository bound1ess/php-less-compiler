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
            $dumped[] = $this->dumpValue($node->getValue());
        }

        return $dumpy->dump($dumped);
    }

    /**
     * @param mixed $value
     * @return array
     */
    protected function dumpValue($value)
    {
        $dumped = [];

        if ( ! is_array($value)) {
            return $value;
        }

        foreach ($value as $key => $anotherValue) {
            if (is_array($anotherValue)) {
                $dumped[$key] = $this->dumpValue($anotherValue);
            } else if ($anotherValue instanceof Node) {
                $dumped[$key] = $this->dumpValue($anotherValue->getValue());
            } else {
                $dumped[$key] = $anotherValue;
            }
        }

        return $dumped;
    }

}
