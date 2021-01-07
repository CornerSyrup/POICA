<?php

/**
 * form data POST method sub-handler.
 *
 * use session data:
 * user:    student id.
 */

namespace controller\apply;

require_once 'model/Handler.php';

class PostHandler extends \model\PostHandler
{
    /**
     * Form handle model.
     *
     * @var IHandleable
     */
    private $model;

    /**
     * Instantiate a new apply POST Handler object. Where data will absorb from php://input.
     *
     * @param Logger $logger Logger.
     */
    public function __construct(\model\Logger $logger)
    {
        parent::__construct($logger);

        switch ($this->data['typ']) {
            case 'doc':
                require_once 'post_DocIssue.php';
                $this->model = new DocIssuePostHandler($this->data['frm']);
                break;
            default:
                throw new \Exception('Unexprected application type.');
        }
    }

    public function Handle(): array
    {
        $this->respond = $this->model->Handle();

        return $this->respond;
    }

    public function Validate(): bool
    {
        return $this->model->Validate();
    }
}
