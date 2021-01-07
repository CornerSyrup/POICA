<?php

/**
 * form data POST method sub-handler.
 *
 * use session data:
 * user:    student id.
 */

namespace controller\apply;

require_once 'model/Handler.php';
require_once 'model/apply/AppForm.php';

use model\app_form as form;

class PostHandler extends \model\PostHandler
{
    /**
     * Form handle model.
     *
     * @var FormRequestHandler
     */
    private form\FormRequestHandler $model;
    /**
     * Name of form.
     */
    private string $form;

    /**
     * Instantiate a new apply POST Handler object. Where data will absorb from php://input.
     *
     * @param Logger $logger Logger.
     * @throws JsonException throw when supplied data unable to be parsed when passing to sub-handler.
     */
    public function __construct(\model\Logger $logger)
    {
        parent::__construct($logger);

        switch ($this->data['typ']) {
            case 'doc':
                require_once 'post_DocIssue.php';
                $this->form = 'doc issue';
                $this->model = new DocIssuePostHandler($this->data['frm']);
                break;
            default:
                throw new \Exception('Unexpected application type.');
        }
    }

    public function Handle(): array
    {
        $this->respond = $this->model->Handle();
        $this->logger->appendRecord("Apply of [{$this->form}] for user [{$_SESSION['user']}] has been done.");

        return $this->respond;
    }

    public function Validate(): bool
    {
        return $this->model->Validate();
    }
}
