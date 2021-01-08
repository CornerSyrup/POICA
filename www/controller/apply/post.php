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
                $this->model = new PostDocIssueHandler($this->data['frm']);
                break;
            default:
                throw new \Exception('Unexpected application type.');
        }
    }

    /**
     * Handle POST request with various form data models.
     *
     * @return array complete respond.
     */
    public function Handle(): array
    {
        try {
            $this->respond['status'] = $this->model->Handle()['status'];
            $this->logger->appendRecord("Apply of [{$this->form}] for user [{$_SESSION['user']}] has been done.");
        } catch (\model\RecordInsertException $rie) {
            $this->logger->appendError($rie);
            $this->respond['status'] = 30;
        } finally {
            return $this->respond;
        }
    }

    /**
     * Check whether supplied data is valid to be handle with models.
     *
     * @return boolean
     */
    public function Validate(): bool
    {
        $valid = false;

        try {
            $valid = $this->model->Validate();

            if (!$valid) {
                $this->logger->appendRecord(
                    "User [{$_SESSION['user']}] attempted to apply, but invalid form data supplied."
                );
            }
        } catch (form\FormIncompleteException $fie) {
            $this->logger->appendError($fie);
            $this->respond['status'] = 31;
        } finally {
            return $valid;
        }
    }
}
