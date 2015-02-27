<?php namespace LessCompiler\Less;

use LessCompiler\Css\Selectors\ElementSelector,
    LessCompiler\Css\Selectors\IdSelector,
    LessCompiler\Css\Selectors\ClassSelector,
    LessCompiler\Css\Selectors\AttributeSelector,
    LessCompiler\Css\Selectors\UniversalSelector,
    LessCompiler\Css\Selectors\PseudoSelector;

use LessCompiler\Css\Combinators\ChildCombinator,
    LessCompiler\Css\Combinators\DescendentCombinator,
    LessCompiler\Css\Combinators\GeneralSiblingCombinator,
    LessCompiler\Css\Combinators\AdjacentSiblingCombinator;

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
     * @param string $value
     * @return \LessCompiler\Less\Query
     */
    protected function parseQuery($value)
    {
        $query = new Query;

        // Normalize spaces.
        $value = preg_replace("/\s{2,}/", " ", $value);

        // Modify attribute selectors.
        $value = str_replace("[", " [", $value);

        // Split and analyze.
        $elements = explode(" ", $value);
        $validName = "(?P<name>[A-Za-z0-9\-\_]+)";

        for ($i = 0; $i < count($elements); $i++) {
            // Pick appropriate selector class.
            $selector = [];

            if (preg_match("/^\[{$validName}(?P<value>.*)\]$/", $elements[$i], $selector)) {
                // Attribute selector.
                $elements[$i] = new AttributeSelector($selector["name"], $selector["value"]);

                continue;
            }

            if ($elements[$i] === "*") {
                // Universal (*) selector.
                $elements[$i] = new UniversalSelector;

                continue;
            }

            if (preg_match("/^{$validName}$/", $elements[$i], $selector)) {
                // Element selector.
                $elements[$i] = new ElementSelector($selector["name"]);

                continue;
            }

            if (preg_match("/^\.{$validName}$/", $elements[$i], $selector)) {
                // Class selector.
                $elements[$i] = new ClassSelector($selector["name"]);

                continue;
            }

            if (preg_match("/^#{$validName}$/", $elements[$i], $selector)) {
                // Id selector.
                $elements[$i] = new IdSelector($selector["name"]);

                continue;
            }

            if (preg_match("/^:{$validName}$/", $elements[$i], $selector)) {
                // "Pseudo" selector.
                $elements[$i] = new PseudoSelector($selector["name"]);

                continue;
            }
        }

        // Now combine the selectors (if possible).
        for ($i = 1; $i < count($elements); $i++) {
            if (is_string($elements[$i])) {
                // Child combinator.
                if ($elements[$i] === ">") {
                    $query->addCombinator(new ChildCombinator(
                        $elements[$i - 1],
                        $elements[$i + 1]
                    ));

                    continue;
                }

                // General sibling combinator.
                if ($elements[$i] === "~") {
                    $query->addCombinator(new GeneralSiblingCombinator(
                        $elements[$i - 1],
                        $elements[$i + 1]
                    ));

                    continue;
                }

                // Adjacent sibling combinator (assumption).
                $query->addCombinator(new AdjacentSiblingCombinator(
                    $elements[$i - 1],
                    $elements[$i + 1]
                ));
            } else if ( ! is_string($elements[$i - 1])) {
                $query->addSelector($elements[$i]);
            }
        }

        var_dump($query->getValue());exit;

        return $query;
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
            $this->parseQuery(trim($info["query"]))
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
