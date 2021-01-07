<?php

/**
 * Doc Issue form data POST method sub-handler.
 * 
 * use session data:
 * user:    student id.
 */

namespace controller\apply;

require_once 'model/DBAdaptor.php';
require_once 'model/Global.php';
require_once 'model/Handler.php';
require_once 'model/apply/DocIssue.php';

use \model\app_form as form;

class DocIssuePostHandler implements \model\IHandleable
{
    /**
     * Result cache.
     *
     * @var array
     */
    private array $result;
    /**
     * Data to be process.
     *
     * @var array
     */
    private array $data;
    /**
     * Form data model object.
     */
    private form\DocIssue $form;

    /**
     * Instantiate a new POST Handler specific for DocIssue AppForm model.
     *
     * @param string $json form data as JSON string.
     * @throws JsonException throw when supplied form data unable to be parsed.
     */
    public function __construct(string $json)
    {
        $this->data = json_parse($json);
    }

    public function GetResult(): array
    {
        return $this->result;
    }

    public function Handle(): array
    {
        if (!isset($this->form)) {
            $this->form = new form\DocIssue();
            $this->form->Deserialize(json_encode($this->data));
        }

        (new \model\DBAdaptor())->insert_form($_SESSION['user'], $this->form);
        $this->reset['status'] = 2;

        return $this->result;
    }

    public function Validate(): bool
    {
        return form\DocIssue::Validate($this->data);
    }
}
