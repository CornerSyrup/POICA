<?php

/**
 * Concat directory and files into local path
 * 
 * @param array list of paths
 */
function join_path(): string
{
    $paths = func_get_args();
    $result = $paths[0];

    for ($i = 1; $i < count($paths); $i++) {
        $result .= DIRECTORY_SEPARATOR . $paths[$i];
    }

    return $result;
}

/**
 * Get the path to the root directory of the web server
 */
function get_server_root(): string
{
    return dirname(__DIR__);
}

/**
 * Stringify value to JSON string.
 *
 * @param mixed $value
 * @return string
 * @throws JsonException
 */
function json_stringify($value): string
{
    return json_encode($value, JSON_THROW_ON_ERROR);
}

/**
 * Parse JSON into assoc array.
 *
 * @param string $json
 * @return array
 * @throws JsonException
 */
function json_parse(string $json): array
{
    return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
}

/**
 * Exception representing inappropriate http request method.
 */
class RequestMethodException extends \Exception
{
    /**
     * Actual http method received.
     *
     * @var string
     */
    public $actual;
    /**
     * Expected http method.
     *
     * @var string
     */
    public $expected;

    /**
     * Constructor of request method exception.
     *
     * @param string $expected expected request method.
     * @param string $actual actual request method received.
     * @param integer $code exception code for exception instance.
     * @param \Exception $innerException inner exception of instance.
     */
    public function __construct(string $expected = '', string $actual, int $code = 0, \Exception $innerException = null)
    {
        $this->actual = $actual;
        $this->expected = $expected;

        $msg = empty($expected) ?
            "Request method expected instead of [{$actual}]." :
            "Request method expected [{$expected}] instead of [{$actual}].";
        parent::__construct($msg, $code, $innerException);
    }
}
