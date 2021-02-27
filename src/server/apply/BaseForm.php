<?php

namespace POICA\apply {

    use POICA\model\Helper;
    use POICA\validation\Validator as valid;
    use POICA\validation\JapaneseValidator as jvalid;

    class BaseForm
    {
        #region fields
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
        #endregion

        #region methods
        /**
         * Check whether basic form data is valid.
         * 
         * @throws FormIncompleteException thrown when any field missing.
         */
        public static function validate(array $data): bool
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
                throw new FormIncompleteException(['bc']);
            }

            return jvalid::is_include_japanese($data['fn'])
                && jvalid::is_include_japanese($data['ln'])
                && jvalid::is_all_katakana($data['fk'])
                && jvalid::is_all_katakana($data['lk'])
                && valid::validate_sid($data['si'])
                && valid::validate_class_code($data['cc'])
                && valid::validate_tid($data['ct']);
        }

        /**
         * Serialize form data into JSON, which must be deserialize with BaseForm deserialize function.
         */
        public function serialize(): string
        {
            // ignore data check, for all field required.
            return Helper::json_stringify([
                'fn' => $this->firstName,
                'fk' => $this->firstKana,
                'ln' => $this->lastName,
                'lk' => $this->lastKana,
                'si' => $this->studentID,
                'cc' => $this->classCode,
                'ct' => $this->classTeacher
            ]);
        }

        /**
         * Deserialize form data JSON into form object, which must be serialized with BaseForm serialize function.
         */
        public function deserialize(string $json)
        {
            // ignore data check, for all field required.
            $data = Helper::json_parse($json);

            $this->firstName = $data['fn'];
            $this->firstKana = $data['fk'];
            $this->lastName = $data['ln'];
            $this->lastKana = $data['lk'];
            $this->studentID = $data['si'];
            $this->classCode = $data['cc'];
            $this->classTeacher = $data['ct'];
        }
        #endregion
    }
}
