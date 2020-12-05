<?php

namespace model;

require_once 'Global.php';

class Logger
{
    private $tag;
    public $dirPath;
    public $fileName;

    /**
     * Initiate an instance of logger.
     *
     * @param string $tag tag for the log record, which should be no longer than 5 letters.
     * @param string $file file name of the log without extension.
     */
    public function __construct(string $tag, string $file = null)
    {
        $this->SetTag($tag);
        $this->dirPath = join_path(dirname(__DIR__), 'logs');
        $this->fileName = is_null($file) ? $tag . '.log' : $file . '.log';

        if (!file_exists($this->dirPath)) {
            mkdir($this->dirPath);
        }
    }

    /**
     * Setter of logging tag.
     *
     * @param string $tag tag for the log record, which should be no longer than 5 letters.
     * @return void
     */
    public function SetTag(string $tag)
    {
        $this->tag = ucfirst($tag);
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
    public function appendError(\Throwable $error): bool
    {
        $msg = "[{$this->tag}]\tError:\t{$error->getMessage()}";

        if ($error->getPrevious() !== null) {
            $msg .= "\n\tIntErr:\t{$error->getPrevious()->getMessage()}";
        }

        return $this->append($msg);
    }

    /**
     * Append record to log file.
     *
     * @param string $msg string of message
     * @return boolean true on success; false on failure.
     */
    private function append(string $msg): bool
    {
        return (bool) file_put_contents(join_path($this->dirPath, $this->fileName), $msg . PHP_EOL, FILE_APPEND);
    }
}
