<?php namespace LessCompiler\Less;

/**
 * LESS parser with a decent performance.
 */
class Parser {

    /**
     * @var \SplQueue
     */
    protected $queue;

    /**
     * @param string $code
     * @return \LessCompiler\Less\LessTree
     */
    public function parse($code)
    {
        $this->intoQueue($code);

        $tree = new LessTree;

        while ( ! $this->queue->isEmpty()) {
            // @do whatever
        }

        return $tree;
    }

    /**
     * @param string $code
     * @return void
     */
    protected function intoQueue($code)
    {
        $this->queue = new \SplQueue;

        foreach (array_filter(array_map("trim", explode(PHP_EOL, $code))) as $line) {
            $this->queue->enqueue($line);
        }
    }

    /**
     * @throws \LessCompiler\Less\Exceptions\ParseException
     * @return string
     */
    protected function readLine()
    {
        try {
            return $this->queue->dequeue();
        } catch(Exceptions\ParseException $exception) {
            // @todo
            throw new Exceptions\ParseException($exception->getMessage());
        }
    }

}
