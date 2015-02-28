<?php namespace LessCompiler\Less;

use LessCompiler\Css\Combinators\Combinator,
    LessCompiler\Css\Selectors\Selector;

/**
 * CSS query parser.
 */
class QueryParser {

    /**
     * @var array
     */
    protected $combinators = [
        "child"           => ">",
        "generalSibling"  => "~",
        "adjacentSibling" => "+",
    ];

    /**
     * @param string $query
     * @return Query
     */
    public function parseQuery($query)
    {
        $elements = $this->split($query);

        $query = new Query;

        foreach ($this->insertCombinators($this->insertSelectors($elements)) as $element) {
            if ($element instanceof Selector) {
                $query->addSelector($element);
            } else if ($element instanceof Combinator) {
                $query->addCombinator($element);
            }
        }

        return $query;
    }

    /**
     * @param string $query
     * @return array
     */
    protected function split($query)
    {
        // Normalize spaces.
        $query = preg_replace("/\s{2,}/", " ", $query);

        // Add spaces where needed.
        $query = preg_replace("/\[/", " [", $query);

        // Split.
        return explode(" ", $query);
    }

    /**
     * @param array $elements
     * @return array
     */
    protected function insertCombinators(array $elements)
    {
        $newElements = [];

        for ($index = 0; $index < count($elements); $index++) {
            $prev = isset ($elements[$index - 1]) ? $elements[$index - 1] : null;
            $next = isset ($elements[$index + 1]) ? $elements[$index + 1] : null;

            if (($elements[$index] instanceof Selector) and ($next instanceof Selector)) {
                $newElements[] = $elements[$index];

                continue;
            }

            foreach ($this->combinators as $id => $symbol) {
                if ($elements[$index] === $symbol) {
                    $newElements[] = $this->createCombinator($id, [$prev, $next]);
                }
            }
        }

        return $newElements;
    }

    /**
     * @param array $elements
     * @return array
     */
    protected function insertSelectors(array $elements)
    {
        $nameRegExp = "(?P<name>[A-Za-z0-9\-\_]+)";

        $regExps = [
            "universal" => "/^\*$/",
            "attribute" => "/^\[{$nameRegExp}(?P<value>.*)\]$/",
            "element"   => "/^{$nameRegExp}$/",
            "class"     => "/^\.{$nameRegExp}$/",
            "pseudo"    => "/^:{$nameRegExp}$/",
            "id"        => "/^#{$nameRegExp}$/",
        ];

        $match = [];
        $newElements = [];

        for ($index = 0; $index < count($elements); $index++) {
            if (in_array($elements[$index], $this->combinators)) {
                $newElements[] = $elements[$index];

                continue;
            }

            foreach ($regExps as $id => $regExp) {
                if (preg_match($regExp, $elements[$index], $match)) {
                    $arguments = [];

                    if (isset ($match["name"])) {
                        $arguments[] = $match["name"];
                    }

                    if (isset ($match["value"])) {
                        $arguments[] = $match["value"];
                    }

                    $newElements[] = $this->createSelector($id, $arguments);

                    continue;
                }
            }
        }

        return $newElements;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return object
     */
    protected function createSelector($name, array $arguments = [])
    {
        $className = sprintf("LessCompiler\\Css\\Selectors\\%sSelector", ucfirst($name));

        return (new \ReflectionClass($className))->newInstanceArgs($arguments);
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return object
     */
    protected function createCombinator($name, array $arguments = [])
    {
        $className = sprintf("LessCompiler\\Css\\Combinators\\%sCombinator", ucfirst($name));

        return (new \ReflectionClass($className))->newInstanceArgs($arguments);
    }

}
