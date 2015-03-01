<?php namespace LessCompiler\Compiler;

/**
 * Variables scope.
 */
class Scope {

    /**
     * @var Scope|null
     */
    protected $parentScope;

    /**
     * @var array
     */
    protected $variables = [];

    /**
     * @param Scope $scope
     * @return void
     */
    public function setParentScope(Scope $scope)
    {
        $this->parentScope = $scope;
    }

    /**
     * @return null|Scope
     */
    public function getParentScope()
    {
        return $this->parentScope;
    }

    /**
     * @return boolean
     */
    public function isMain()
    {
        return is_null($this->parentScope);
    }

    /**
     * @param string $name
     * @param string $value
     * @throws Exceptions\VarAlreadyDefinedException
     * @return void
     */
    public function setVariable($name, $value)
    {
        if (array_key_exists($name, $this->variables)) {
            throw new Exceptions\VarAlreadyDefinedException($name);
        }

        $this->variables[$name] = $value;
    }

    /**
     * @param string $name
     * @throws Exceptions\UndefinedVariableException
     * @return string
     */
    public function resolve($name)
    {
        if (array_key_exists($name, $this->variables)) {
            return $this->variables[$name];
        }

        if ( ! is_null($this->parentScope)) {
            return $this->parentScope->resolve($name);
        }

        throw new Exceptions\UndefinedVariableException($name);
    }

    /**
     * @param string $value
     * @return string
     */
    public function interpolate($value)
    {
        // ...
    }

}
