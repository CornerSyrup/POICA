<?php

/**
 * General functions and class for application form
 */

namespace model\app_form;

use JsonException;

require_once 'model/Global.php';
require_once 'model/Validation';

use model\validation as valid;

class AppForm
{
    /**
     * First name of the applicant.
     */
    public string $firstName;
    /**
     * First name kana of the applicant.
     */
    public string $firstKana;
    /**
     * Last name of the applicant.
     */
    public string $lastName;
    /**
     * Last name kana of teh applicant.
     */
    public string $lastKana;
    /**
     * Student ID of the applicant.
     */
    public string $studentID;
    /**
     * Class code which the applicant affiliated.
     */
    public string $classCode;
    /**
     * Teacher employee code of the applicant class teacher.
     */
    public string $classTeacher;

    /**
     * Check whether basic form data is valid.
     *
     * @param array $data basic form data.
     * @return boolean
     * @throws FormIncompleteException thrown when any field missing.
     */
    public static function Validate(array $data): bool
    {
        if (
            !(isset($data['fn']) &&
                isset($data['ln']) &&
                isset($data['fk']) &&
                isset($data['lk']) &&
                isset($data['si']) &&
                isset($data['cc']) &&
                isset($data['ct']))
        ) {
            throw new FormIncompleteException('bc');
        }

        return valid\validate_jname($data['fn'])
            && valid\validate_jname($data['ln'])
            && valid\validate_jkana($data['fk'])
            && valid\validate_jkana($data['lk'])
            && valid\validate_sid($data['si'])
            && valid\validate_class_code($data['cc'])
            && valid\validate_tid($data['ct']);
    }

    /**
     * Serialize form data into json, which must be deserialize with AppForm deserialize function.
     *
     * @return string Serialized json as string.
     */
    public function Serialize(): string
    {
        // ignore data check, for all field required.
        $data = [
            'fn' => $this->firstName,
            'fk' => $this->firstKana,
            'ln' => $this->lastName,
            'lk' => $this->lastKana,
            'si' => $this->studentID,
            'cc' => $this->classCode,
            'ct' => $this->classTeacher
        ];

        return json_encode($data);
    }

    /**
     * Deserialize form data json string into form data, which must be serialized with AppForm serialize function.
     *
     * @param string $json Serialized form data with AppForm serialized function.
     * @throws JsonException
     */
    public function Deserialize(string $json)
    {
        // ignore data check, for all field required.
        $data = json_parse($json);

        $this->firstName = $data['fn'];
        $this->firstKana = $data['fk'];
        $this->lastName = $data['ln'];
        $this->lastKana = $data['lk'];
        $this->studentID = $data['si'];
        $this->classCode = $data['cc'];
        $this->classTeacher = $data['ct'];
    }
}

/**
 * Exception representing form incompleteness.
 */
class FormIncompleteException extends \Exception
{
    /**
     * Constructor of form incomplete exception.
     *
     * @param string $field field that not filled.
     * @param string $reason reason of the supplied field required.
     * @param integer $code error code.
     * @param \Exception $innerException internal exception which raised this exception indirectly.
     */
    public function __construct(string $field, string $reason = 'required', int $code = 0, \Exception $innerException = null)
    {
        parent::__construct(
            "Field [{$field}] required for [{$reason}] but remain empty.",
            $code,
            $innerException
        );
    }
}

class FormInvalidException extends \Exception
{
    /**
     * Constructor of form invalid exception.
     *
     * @param string $field field that is invalid.
     * @param string $reason reason of the supplied data invalid.
     * @param integer $code error code.
     * @param \Exception $innerException internal exception which raised this exception indirectly.
     */
    public function __construct(string $field, string $reason = '', int $code = 0, \Exception $innerException = null)
    {
        $msg = empty($reason) ?
            "Field [{$field}] contain invalid data." :
            "Field [{$field}] contain invalid data for [{$reason}].";
        parent::__construct(
            $msg,
            $code,
            $innerException
        );
    }
}
