<?php namespace PhpLessCompiler;

use PhpLessCompiler\Parser\Parser,
    PhpLessCompiler\Parser\Statements\DeclarationStatement,
    PhpLessCompiler\Parser\Statements\VarStatement,
    PhpLessCompiler\Parser\Statements\ImportStatement;

use PhpLessCompiler\Compiler\ScopeManager;

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

        foreach ($nodes as $node) {
            // Add a variable to "global" scope.
            if ($node instanceof VarStatement) {
                $var = $node->get();

                $this->scopeManager->get('global')->set($var['name'], $var['value']);
            }

            // Handle imports.
            // @todo

            if (is_array($node)) {
                foreach ($node['nodes'] as $element) {
                    // Handle everything else.
                    // @todo
                    if ($element instanceof DeclarationStatement) {
                        $scope = $this->scopeManager->getOrCreate($node['selector']);
                        $new[$node['selector']][] = $element;

                        $element->apply($scope);
                    }
                }
            }
        }

        return $new;
    }

    /**
     * @param array $nodes
     * @return string
     */
    protected function printCss(array $nodes)
    {
        $output = '';

        foreach ($nodes as $selector => $declarations) {

            foreach ($declarations as $index => $declaration) {
                $declaration = $declaration->get();

                $declarations[$index] = declaration(
                    property($declaration['property']),
                    value($declaration['value'])
                );
            }

            $box = call_user_func_array('box', $declarations);

            $output .= $this->printer->doPrint($box->attach($selector));
        }

        return $output;
    }
}
