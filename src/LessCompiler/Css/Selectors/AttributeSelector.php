<?php namespace LessCompiler\Css\Selectors;

/**
 * This selector selects all elements with a specific attribute and/or its value.
 */
class AttributeSelector extends Selector {

    /**
     * {@inheritdoc}
     */
    public function represent()
    {
        if (is_null($this->value["attribute_value"])) {
            return sprintf("[%s]", $this->value["attribute_name"]);
        }

        return sprintf(
            "[%s=\"%s\"]",
            $this->value["attribute_name"],
            $this->value["attribute_value"]
        );
    }

}
