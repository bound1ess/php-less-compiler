<?php namespace PhpLessCompiler;

use PhpLessCompiler\Parser\Parser,
    PhpLessCompiler\Parser\Statements\DeclarationStatement,
    PhpLessCompiler\Parser\Statements\VarStatement,
    PhpLessCompiler\Parser\Statements\ImportStatement,
    PhpLessCompiler\Parser\Statements\MixinStatement;

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
     * @var array
     */
    protected $mixins = [];

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

        $this->mixins = [];

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

                if ( ! array_key_exists('is_mixin', $node)) {

                    $scope = $this->findScope($selector = $node['selector']);
                    $selector = $scope->interpolate($selector);

                } else {
                    // That's a mixin.
                    $this->mixins[] = $node;

                    continue;
                }

                $this->setVars($scope, $node['nodes']);

                foreach ($node['nodes'] as $element) {

                    if ($this->isVar($element)) {
                        continue;
                    }

                    if ($element instanceof MixinStatement) {

                        if ( ! array_key_exists($selector, $new)) {
                            $new[$selector] = [];
                        }

                        $new[$selector] = $this->handleMixin(
                            $element, $new[$selector], $scope
                        );

                        continue;
                    }

                    if ($this->isImport($element)) {
                        $new[$selector][] = $this->handleImport($element);

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
        if (in_array($mode, ['less', 'css']) and strpos($fullPath, '.' . $mode) === false) {
            $fullPath .= '.' . $mode;
        }

        switch ($mode) {

            case 'less':
                return $this->compile(file_get_contents($fullPath));

            case 'css':
                return sprintf('@import "%s";', $src);

            case 'inline':
                return trim(file_get_contents($fullPath));

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
     * @param MixinStatement $mixin
     * @param array $nodes
     * @param Scope $scope
     * @return array
     */
    protected function handleMixin(MixinStatement $mixin, array $nodes, Scope $scope)
    {
        // Find the requested mixin.
        $requested = null;

        foreach ($this->mixins as $someMixin) {
            if (strpos($someMixin['selector'], $name = $mixin->get()['name']) === 0) {
                $requested = $someMixin;

                break;
            }
        }

        // Couldn't find it.
        if (is_null($requested)) {
            throw new Exceptions\UndefinedMixinException($name);
        }

        // Create a dedicated scope for the requested mixin.
        $isolatedScope = new Scope($name);
        $args = $mixin->get()['args'];

        foreach ($someMixin['args'] as $position => $arg) {

            $isolatedScope->set(
                str_replace('@', '', $arg),
                $scope->interpolate($args[$position])
            );
        }

        // Apply it.
        foreach ($someMixin['nodes'] as $node) {
            $nodes[] = $node;

            $node->apply($isolatedScope);
        }

        // Done.
        return $nodes;
    }

    /**
     * @param array $nodes
     * @return string
     */
    protected function printCss(array $nodes)
    {
        $output = [];

        foreach ($nodes as $selector => $declarations) {

            if (is_int($selector)) {
                $output[] = $declarations;

                continue;
            }

            foreach ($declarations as $index => $declaration) {

                if (is_string($declaration)) {
                    $declarations[$index] = literal($declaration);

                    continue;
                }

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
