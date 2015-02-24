<?php namespace LessCompiler;

/**
 * A traversable AST.
 */
class AbstractSyntaxTree implements \IteratorAggregate {

    /**
     * @var array
     */
    protected $nodes = [];

    /**
     * @param array $nodes
     * @return AbstractSyntaxTree
     */
    public function __construct(array $nodes = [])
    {
        $this->addNodes($nodes);
    }

    /**
     * @param array $nodes
     * @return void
     */
    public function addNodes(array $nodes)
    {
        foreach ($nodes as $node) {
            $this->addNode($node);
        }
    }

    /**
     * @param Node $node
     * @return void
     */
    public function addNode(Node $node)
    {
        $this->nodes[] = $node;
    }

    /**
     * @return array
     */
    public function getNodes()
    {
        return $this->nodes;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->nodes);
    }

}
