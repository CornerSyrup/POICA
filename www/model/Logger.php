<?php

namespace model;

use Throwable;

require 'Global.php';

class Logger
{
    public $tag;
    public $dirPath;
    public $fileName;

    /**
     * Initiate an instance of logger.
     *
     * @param string $tag tag for the log record.
     * @param string $file file name of the log without extension.
     */
    public function __construct(string $tag, string $file = null)
    {
        $this->tag = $tag;
        $this->dirPath = join_path(dirname(__DIR__), 'logs');
        $this->fileName = is_null($file) ? $tag . '.log' : $file . '.log';

        if (!file_exists($this->dirPath)) {
            mkdir($this->dirPath);
        }
    }

    /**
     * Append a new record to log file with given message string.
     *
     * @param string $msg body part of the log record.
     * @return boolean true on success append; false on failure.
     */
    public function appendRecord(string $msg): bool
    {
        if (!empty($this->tag)) {
            $msg = "[{$this->tag}]\t{$msg}";
        }

        return $this->append($msg);
    }

    /**
     * Append a new record of error to log file with given exception instance.
     *
     * @param Throwable $error instance of the exception.
     * @return boolean true on success append; false on failure.
     */
    public function appendError(Throwable $error): bool
    {
        $msg = empty($this->tag) ?
            "Error:\t{$error->getMessage()}" :
            "[{$this->tag}] Error:\n{$error->getMessage()}";

        return $this->append($msg);
    }

    private function append(string $data): bool
    {
        return (bool) file_put_contents(join_path($this->dirPath, $this->fileName), $data . PHP_EOL, FILE_APPEND);
    }
}
