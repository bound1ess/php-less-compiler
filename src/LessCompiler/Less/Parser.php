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
     * @var boolean
     */
    protected $inComment = false;

    /**
     * @param string $code
     * @return \LessCompiler\Less\LessTree
     */
    public function parse($code)
    {
        $this->intoQueue($code);

        $tree = new LessTree;

        while ( ! $this->queue->isEmpty()) {
            if ($this->isComment($line = $this->readLine())) {
                continue;
            }

            if (strlen($line = $this->removeComments($line)) === 0) {
                continue;
            }

            // File imports.
            if ( ! is_null($import = $this->detectImportStatement($line))) {
                $tree->addNode($import);
            }

            // Variable assignments.
            if ( ! is_null($assignment = $this->detectVariableAssignment($line))) {
                $tree->addNode($assignment);
            }
        }

        return $tree;
    }

    /**
     * @param string $line
     * @return \LessCompiler\Less\Statements\VarAssignmentStatement|null
     */
    protected function detectVariableAssignment($line)
    {
        $info = [];

        if ( ! preg_match("/^@(?P<name>\w+):(?P<value>.+);$/", trim($line), $info)) {
            return null;
        }

        return new Statements\VarAssignmentStatement(
            $info["name"],
            trim($info["value"])
        );
    }

    /**
     * @param string $line
     * @return \LessCompiler\Less\Statements\ImportStatement|null
     */
    protected function detectImportStatement($line)
    {
        $info = [];

        if (preg_match("/^@import (?P<mode>\(\w+\)) (?P<file>.+)$/", $line, $info)) {
            return new Statements\ImportStatement(
                preg_replace("/[\'\"]+/", "", $info["file"]),
                trim(preg_replace("/[\(\)]{1}/", "", $info["mode"]))
            );
        }

        if ( ! preg_match("/^@import (?P<file>.+)$/", $line, $info)) {
            return null;
        }

        return new Statements\ImportStatement(
            trim(preg_replace("/[\'\"]+/", "", $info["file"]))
        );
    }

    /**
     * @param string $line
     * @return boolean
     */
    protected function isComment($line)
    {
        $line = trim($line);

        if (strpos($line, "/*") === 0) {
            $this->inComment = true;
        }

        if (strpos(strrev($line), "/*") === 0) {
            $this->inComment = false;

            return true;
        }

        return $this->inComment;
    }

    /**
     * @param string $line
     * @return string
     */
    protected function removeComments($line)
    {
        // Remove // comments.
        $line = preg_replace("/\/\/.+$/", "", $line);

        // As well as /* ... */ one-liners.
        return trim(preg_replace("/\/\*.+\*\//", "", $line));
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
        } catch(\RuntimeException $exception) {
            // @todo
            throw new Exceptions\ParseException($exception->getMessage());
        }
    }

}
