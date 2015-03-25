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
     * @var string
     */
    protected $root;

    /**
     * @param string|null $root
     * @return Compiler
     */
    public function __construct($root = null)
    {
        $this->parser = new Parser;

        $this->scopeManager = new ScopeManager;

        $this->printer = Printer::make('default');

        $this->root = $root ?: getcwd();
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

            if ($this->isImport($node)) {
                $new[] = $this->handleImport($node);
            }

            if (is_array($node)) {
                $scope = $this->findScope($node['selector']);
                $selector = $scope->interpolate($node['selector']);

                $this->setVars($scope, $node['nodes']);

                foreach ($node['nodes'] as $element) {

                    if ($this->isVar($element)) {
                        continue;
                    }

                    if ($this->isImport($node)) {
                        $new[$selector][] = $this->handleImport($node);

                        continue;
                    }

                    if ($element instanceof DeclarationStatement) {
                        $new[$selector][] = $element;

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
     * @param array|object $node
     * @return bool
     */
    protected function isImport($node)
    {
        return is_object($node) and ($node instanceof ImportStatement);
    }

    /**
     * @param ImportStatement $import
     * @return string
     */
    protected function handleImport(ImportStatement $import)
    {
        $src = $import->get()['src'];
        $mode = $import->get()['mode'];

        $fullPath = $this->root . '/' . $src;

        // file ==> file.less if mode is set to "less"
        // but file.less ==> file.less, no changes
        if (strpos($fullPath, '.' . $mode) === false) {
            $fullPath .= '.' . $mode;
        }

        switch ($mode) {

            case 'less':
                return $this->compile(file_get_contents($fullPath));

            case 'css':
                return sprintf('@import "%s";', $src);

            case 'inline':
                return file_get_contents($fullPath);

            default:
                throw new Exceptions\NotSupportedException("Import mode #{$mode}");
        }
    }

    /**
     * @param string $selector
     * @return Scope
     */
    protected function findScope($selector)
    {
        $parent = 'global';

        foreach ($this->scopeManager->scopes() as $scope) {

            if (strpos($selector, $scope->id()) === 0) {
                $parent = $scope->id();

                break;
            }
        }

        return $this->scopeManager->create($selector, $parent);
    }

    /**
     * @param array $nodes
     * @return string
     */
    protected function printCss(array $nodes)
    {
        $output = [];

        foreach ($nodes as $selector => $declarations) {

            if (is_string($declarations)) {
                var_dump($declarations);exit;
            }

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
