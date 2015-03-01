<?php namespace LessCompiler\Css\Selectors;

/**
 * This selector selects all elements with a specific attribute and/or its value.
 */
class AttributeSelector extends Selector {

    /**
     * @param string $name
     * @param string|null $value
     * @return AttributeSelector
     */
    public function __construct($name, $value = null)
    {
        parent::__construct([
            "attribute_name"  => $name,
            "attribute_value" => preg_replace("/[=\"\']+/", "", $value),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function represent()
    {
        if ( ! $this->value["attribute_value"]) {
            return sprintf("[%s]", $this->value["attribute_name"]);
        }

        return sprintf(
            "[%s=\"%s\"]",
            $this->value["attribute_name"],
            $this->value["attribute_value"]
        );
    }

}
