<?php namespace LessCompiler\Compiler;

/**
 * Scope manager.
 */
class ScopeManager {

    /**
     * @var array
     */
    protected $scopes = [];

    /**
     * @return ScopeManager
     */
    public function __construct()
    {
        // Add "global" scope.
        $this->scopes["global"] = new Scope;
    }

    /**
     * @param string $id
     * @param Scope|null $parentScope
     * @return Scope
     */
    public function getScope($id, Scope $parentScope = null)
    {
        if ( ! array_key_exists($id, $this->scopes)) {
            $this->scopes[$id] = new Scope;
            $this->scopes[$id]->setParentScope($parentScope ?: $this->scopes["global"]);
        }

        return $this->scopes[$id];
    }
}
