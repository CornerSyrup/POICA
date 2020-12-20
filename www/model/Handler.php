<?php

namespace model;

require_once 'model/Logger';

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
    private $data;
    /**
     * Logger for handler.
     *
     * @var Logger
     */
    private $logger;
    /**
     * Respond to the request.
     *
     * @var array
     */
    private $respond;

    public function __construct(Logger $logger, $data = null)
    {
        $this->logger = $logger;
        $this->data = $data ?? json_decode(file_get_contents('php://input'), true);
    }
}

abstract class GetHandler extends Handler
{
    public function __construct(Logger $logger, $data = null)
    {
        parent::__construct($logger, $data);
        $this->logger->SetTag('get');
    }
}

abstract class PostHandler extends Handler
{
    public function __construct(Logger $logger, $data = null)
    {
        parent::__construct($logger, $data);
        $this->logger->SetTag('post');
    }
}
