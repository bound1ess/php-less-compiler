<?php namespace LessCompiler\Less\Statements;

/**
 * Represents LESS "import" directive.
 */
class ImportStatement extends \LessCompiler\Node {

    /**
     * @var array
     */
    protected $modes = [
        "less",
        "css",
        "reference",
        "inline",
        "optional",
        "once",
        "multiple",
    ];

    /**
     * @param string $file
     * @param string|null $mode
     * @return ImportStatement
     */
    public function __construct($file, $mode = null)
    {
        parent::__construct([
            "file" => $file,
            "mode" => $mode,
        ]);
    }

    /**
     * @return string
     */
    public function getFullFilePath()
    {
        $file = $this->value["file"];

        if (strpos($file, "/") === 0) {
            return $file;
        }

        return sprintf("%s/%s", getcwd(), $file);
    }

    /**
     * @return string
     */
    public function getMode()
    {
        if ( ! is_null($mode = $this->value["mode"])) {
            return $mode;
        }

        return $this->guessMode();
    }

    /**
     * @return string
     */
    protected function guessMode()
    {
        $extension = explode(".", $this->value["file"]);
        $extension = end($extension);

        if (in_array($extension, $this->modes)) {
            return $extension;
        }

        return "less";
    }

}
