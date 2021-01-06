<?php

namespace model;

require_once 'model/Logger.php';
require_once 'model/Localizer.php';

/**
 * Interface implement to handler request
 */
interface IHandleable
{
    /**
     * Get result handling result.
     *
     * @return array result as array, null return if not yet handled.
     */
    function GetResult(): array;

    /**
     * Handle of the request.
     *
     * @return array result as array.
     */
    function Handle(): array;

    /**
     * Validate whether data input is legal.
     *
     * @return boolean true if valid, false if invalid.
     */
    function Validate(): bool;
}

/**
 * Model for sub handlers which attached to entry point.
 */
abstract class Handler implements IHandleable
{
    /**
     * Data obtain from php input.
     *
     * @var array
     */
    protected $data;
    /**
     * Logger for handler.
     *
     * @var Logger
     */
    protected $logger;
    /**
     * Respond to the request.
     *
     * @var array
     */
    protected $respond;

    /**
     * Instantiate a new Handler object.
     *
     * @param Logger $logger Logger.
     * @param array $data Data array, usually POST, GET, REQUEST. If null given, it will parse data from php://input.
     */
    public function __construct(Logger $logger, array $data = null)
    {
        $this->logger = $logger;
        $this->data = $data ?? json_decode(file_get_contents('php://input'), true);
        $this->data = \model\Localizer::LocalizeArray($data);
    }

    public function GetResult(): array
    {
        return empty($this->respond) ? null : $this->respond;
    }
}

abstract class GetHandler extends Handler
{
    /**
     * Instantiate a new GET Handler object.
     *
     * @param Logger $logger Logger.
     * @param array $data Data array, usually $_GET. If null given, it will parse data from php://input.
     */
    public function __construct(Logger $logger, $data = null)
    {
        parent::__construct($logger, $data);
        $this->logger->SetTag('get');
    }
}

abstract class PostHandler extends Handler
{
    /**
     * Instantiate a new POST Handler object.
     *
     * @param Logger $logger Logger.
     * @param array $data Data array, usually $_POST. If null given, it will parse data from php://input.
     */
    public function __construct(Logger $logger, $data = null)
    {
        parent::__construct($logger, $data);
        $this->logger->SetTag('post');
    }
}
