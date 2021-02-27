<?php

namespace POICA\model {
    /**
     * Logging service provider.
     */
    class Logger
    {
        private string $tag;
        private string $file;

        /**
         * Initiate an instance of logger.
         *
         * @param string $tag Tag for log record.
         * @param string $file File name of the log file without extension.
         */
        public function __construct(string $tag, string $file = null)
        {
            $this->set_tag($tag);
            $dir = getcwd() . DIRECTORY_SEPARATOR . 'logs';
            $this->file = $dir . DIRECTORY_SEPARATOR . (is_null($file) ? $tag : $file) . '.log';

            if (!file_exists($dir)) mkdir($dir);
        }

        /**
         * Setter of the log tag, which should not longer then 5 char.
         * 
         * @throws InvalidArgumentException throws when supplied tag is empty.
         */
        public function set_tag(string $value)
        {
            if (empty($value))
                throw new \InvalidArgumentException(
                    'Logger tag should not be empty. If empty tag is intensional, try static method.'
                );

            $this->tag = ucfirst($value);
        }

        /**
         * Append a new message to log file.
         */
        public function append_record(string $msg): bool
        {
            return self::append("[{$this->tag}]\t{$msg}", $this->file);
        }

        public function append_error(\Throwable $exception): bool
        {
            $msg = "[{$this->tag}]\tError:\t{$exception->getMessage()}";

            if (!is_null($exception->getPrevious())) {
                $msg .= PHP_EOL . "\t\tIntErr:\t{$exception->getPrevious()->getMessage()}";
            }

            return self::append($msg, $this->file);
        }

        /**
         * Append message to log file with blank line.
         *
         * @param string $msg Message to be append.
         * @param string $file file in full path.
         */
        public static function append(string $msg, string $file): bool
        {
            return file_put_contents($file, $msg . PHP_EOL, FILE_APPEND);
        }
    }
}
