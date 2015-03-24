<?php namespace PhpLessCompiler;

use PhpLessCompiler\Parser\Parser,
    PhpLessCompiler\Parser\Statements\DeclarationStatement,
    PhpLessCompiler\Parser\Statements\VarStatement,
    PhpLessCompiler\Parser\Statements\ImportStatement;

use PhpLessCompiler\Compiler\ScopeManager,
    PhpLessCompiler\Compiler\Scope;

use PhpLessCompiler\CssPrinter\Printer,
    PhpLessCompiler\CssPrinter\Printers\Printer as PrinterContract;

class Compiler {

    /**
     * @var Parser
     */
    protected $parser;

    /**
     * @var ScopeManager
     */
    protected $scopeManager;

    /**
     * @var PrinterContract
     */
    protected $printer;

    /**
     * @return Compiler
     */
    public function __construct()
    {
        $this->parser = new Parser;

        $this->scopeManager = new ScopeManager;

        $this->printer = Printer::make('default');
    }

    /**
     * @param string $less
     * @return string
     */
    public function compile($less)
    {
        $this->scopeManager->clean();

        $tree = $this->parser->parse($less);

        return $this->printCss($this->doCompile($tree));
    }

    /**
     * @param array $nodes
     * @return array
     */
    protected function doCompile(array $nodes)
    {
        $new = [];

        $this->setVars($this->scopeManager->get('global'), $nodes);

        foreach ($nodes as $node) {

            if ($this->isVar($node)) {
                continue;
            }

            // Handle imports.
            // @todo

            if (is_array($node)) {
                $scope = $this->scopeManager->getOrCreate($node['selector']);

                $this->setVars($scope, $node['nodes']);

                foreach ($node['nodes'] as $element) {

                    if ($this->isVar($element)) {
                        continue;
                    }

                    // Handle everything else.
                    // @todo

                    if ($element instanceof DeclarationStatement) {
                        $new[$scope->interpolate($node['selector'])][] = $element;

                        $element->apply($scope);
                    }
                }
            }
        }

        return $new;
    }

    /**
     * @param Scope $scope
     * @param array $nodes
     * @return void
     */
    protected function setVars(Scope $scope, array $nodes)
    {
        foreach ($nodes as $node) {

            if ($this->isVar($node)) {
                $scope->set($node->get()['name'], $node->get()['value']);
            }
        }
    }

    /**
     * @param array|object $node
     * @return bool
     */
    protected function isVar($node)
    {
        return is_object($node) and ($node instanceof VarStatement);
    }

    /**
     * @param array $nodes
     * @return string
     */
    protected function printCss(array $nodes)
    {
        $output = [];

        foreach ($nodes as $selector => $declarations) {

            foreach ($declarations as $index => $declaration) {
                $declaration = $declaration->get();

                $declarations[$index] = declaration(
                    property($declaration['property']),
                    value($declaration['value'])
                );
            }

            $box = call_user_func_array('box', $declarations);

            $output[] = $this->printer->doPrint($box->attach($selector));
        }

        return implode(PHP_EOL . PHP_EOL, $output);
    }
}
