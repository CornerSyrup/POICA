<?php

namespace model;

class Logger
{
    public $tag;
    public $fileName;

    /**
     * Initiate an instance of logger
     *
     * @param string $tag tag for the log record
     * @param string $file file name of the log without extension
     */
    public function __construct(string $tag = 'Event', string $file = 'general')
    {
        $this->tag = $tag;
        $this->fileName = $file;
    }

    /**
     * Append a new record to log file with given detail string
     *
     * @param string $detail body part of the log record
     * @return boolean true on success put; false on failure
     */
    public function appendRecord(string $detail): bool
    {
        $dirPath = '../logs/';

        if (!file_exists($dirPath)) {
            mkdir($dirPath);
        }

        if (!empty($this->tag)) {
            $detail = "{$this->tag}\t{$detail}";
        }

        return (bool) file_put_contents("{$dirPath}/{$this->fileName}.log", $detail . PHP_EOL, FILE_APPEND);
    }
}
