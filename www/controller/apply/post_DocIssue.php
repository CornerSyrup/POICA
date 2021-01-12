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
require_once 'model/Localizer.php';
require_once 'model/apply/AppForm.php';
require_once 'model/apply/DocIssue.php';

use \model\app_form as form;

class PostDocIssueHandler extends form\FormRequestHandler
{
    /**
     * Instantiate a new POST Handler specific for DocIssue AppForm model.
     *
     * @param string $json form data as JSON string.
     * @throws JsonException throw when supplied form data unable to be parsed.
     */
    public function __construct(string $json)
    {
        $this->data = json_parse($json);

        if (isset($this->data['gs'])) {
            $this->data['gs'] = json_parse($this->data['gs']);
        }
        if (isset($this->data['is'])) {
            $this->data['is'] = json_parse($this->data['is']);
        }

        $this->data = \model\Localizer::LocalizeArray($this->data);

        if (isset($this->data['gs'])) {
            $this->data['gs'] = json_stringify($this->data['gs']);
        }
        if (isset($this->data['is'])) {
            $this->data['is'] = json_stringify($this->data['is']);
        }
    }

    /**
     * Get last handle result cache.
     *
     * @return array complete respond.
     */
    public function GetResult(): array
    {
        return $this->result;
    }

    /**
     * Handle POST request with DocIssue form data model.
     *
     * @return array complete respond.
     * @throws RecordInsertException throw when any problem encountered while inserting form data into database.
     */
    public function Handle(): array
    {
        if (!isset($this->form)) {
            $this->form = new form\DocIssue();
            $this->form->Deserialize(json_encode($this->data));
        }

        (new \model\DBAdaptor())->insert_form($_SESSION['user'], $this->form);
        $this->result['status'] = 2;

        return $this->result;
    }

    /**
     * Check whether supplied data is valid to be handle.
     *
     * @return boolean
     * @throws FormIncompleteException throw when required field missing.
     */
    public function Validate(): bool
    {
        return form\DocIssue::Validate($this->data);
    }
}
