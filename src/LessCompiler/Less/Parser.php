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
            try {
                $line = $this->readLine();
            } catch (Exceptions\ParseException $exception) {
                break;
            }

            // File imports.
            if ( ! is_null($import = $this->detectImportStatement($line))) {
                $tree->addNode($import);
            }

            // Variable assignments.
            if ( ! is_null($assignment = $this->detectVariableAssignment($line))) {
                $tree->addNode($assignment);
            }

            // Rules.
            if ( ! is_null($rule = $this->detectRule($line))) {
                $tree->addNode($rule);
            }
        }

        return $tree;
    }

    /**
     * @param string $line
     * @param integer $brackets
     * @return null|\LessCompiler\Less\Container
     */
    protected function detectRule($line, $brackets = 1)
    {
        $info = [];

        if ( ! preg_match($ruleRegex = "/^(?P<query>.+)\{" . "$/", trim($line), $info)) {
            return null;
        }

        $container = new Container(
            new Query(/* $this->parseQuery(...) */)
        );

        do {
            try {
                $line = $this->readLine();
            } catch (Exceptions\ParseException $exception) {
                break;
            }

            // Support nested rules.
            if (preg_match($ruleRegex, $line)) {
                $container->addChildContainer($this->detectRule($line));

                continue;
            }

            // Maintain brackets balance.
            if (strpos($line, "}") !== false) {
                --$brackets;
            }

            if ( ! preg_match("/^(?P<name>\w+):(?P<value>.+);$/", $line, $property)) {
                continue;
            }

            $container->addProperty(new Property(
                $property["name"],
                trim($property["value"])
            ));
        } while ($brackets !== 0);

        return $container;
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
            $line = trim($this->queue->dequeue());
        } catch(\RuntimeException $exception) {
            throw new Exceptions\ParseException;
        }

        if ($this->isComment($line) or ! strlen($line = trim($this->removeComments($line)))) {
            return $this->readLine();
        }

        return $line;
    }

}
