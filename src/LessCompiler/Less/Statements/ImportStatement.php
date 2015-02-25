<?php namespace LessCompiler\Less\Statements;

/**
 * Represents LESS "import" directive.
 */
class ImportStatement extends \LessCompiler\Node {

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

        if ($fullPath = file_exists(getcwd()."/{$file}")) {
            return $fullPath;
        }

        return $file;
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

}
