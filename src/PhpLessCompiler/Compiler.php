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
        $tree = $this->parser->parse($less);

        return $this->printCss($this->doCompile($tree));
    }

    /**
     * @param array $nodes
     * @return array
     */
    protected function doCompile(array $nodes)
    {
        return $nodes;
    }

    /**
     * @param array $nodes
     * @return string
     */
    protected function printCss(array $nodes)
    {
        $output = '';

        foreach ($nodes as $node) {
            $box = call_user_func_array('box', $node['nodes']);

            $output .= $this->printer->doPrint($box->attach($node['selector']));
        }

        return $output;
    }
}
