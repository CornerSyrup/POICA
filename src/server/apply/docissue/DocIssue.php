<?php

namespace POICA\apply\docissue {

    use POICA\apply as apply;
    use POICA\model\Helper;
    use POICA\validation\Validator as valid;

    /**
     * Represent the application form of document issue application,
     * aka 証明書等発行願.
     */
    class DocIssue extends apply\BaseForm
    {
        #region fields
        /**
         * Unix timestamp of date of birth, set with function setter SetDateOfBirth.
         */
        private int $dob;
        /**
         * Status of the applicant.
         * 1 as 在校生, 2 as 卒業生, 3 as 休学者, 4 as 退学者
         */
        public int $status;
        /**
         * Purpose of apply.
         * 1 as 進学, 2 as 国家試験, 3 as Visa申請手続, 4 as 旅行
         */
        public int $purpose;
        /**
         * Number of carbon copy desired for each document in array.
         * Where 5, 6 accept only 1 copy, more than 1 will be treat as 1.
         * 3, 4 require fill in graduate sub-form.
         * 6 require fill in sub form.
         * 1 for 在学証明書, 2 for 成績証明書, 3 for 卒業証明書, 4 for 卒業見込証明書, 5 for 勤労学生控除に関する証明書, 6 for 留学生学業成績および出席状況調書, 7 for 所属機関フォーム.
         */
        public array $document;
        /**
         * English name of the applicants.
         * Which only require when any document need to be issue in english.
         */
        public string $enName;
        /**
         * Language of the document to be issued.
         * False for Japanese, true for English.
         * Only 1~4 accepted.
         */
        public array $language;
        #endregion

        #region sub forms
        /**
         * Sub form for international student only and when require.
         */
        public ResultAttendanceSubForm $interSub;
        /**
         * Sub form for graduates students who desired to apply graduation related documents
         */
        public GraduatesSubForm $gradSub;
        #endregion

        #region properties
        /**
         * Getter for date of birth.
         *
         * @return int
         */
        public function get_date_of_birth(): int
        {
            return $this->dob;
        }

        /**
         * Setter for date of birth.
         *
         * @param int $year
         * @param int $month
         * @param int $day
         * @return void
         */
        public function set_date_of_birth(int $year, int $month, int $day): void
        {
            $this->dob = mktime(null, null, null, $month, $day, $year);
        }
        #endregion

        #region methods
        /**
         * Serialize form data into json, which must be deserialize with DocIssue deserialize function.
         *
         * @return string Serialized json as string.
         * @throws apply\FormIncompleteException throw when some required field or sub-fom missing.
         */
        public function serialize(): string
        {
            /**
             * keys name and meaning as follow:
             * st   : status
             * db   : date of birth
             * pp   : purpose
             * en   : english name
             * dc   : documents
             * lg   : language
             *
             * keys name for sub form:
             * gs   : graduates sub form
             * is   : international student sub form
             *
             * key for parent:
             * bc   : base class, AppForm
             */

            #region check required
            if (empty($this->purpose))
                throw new apply\FormIncompleteException(['purpose'], 'required');
            if (empty($this->status))
                throw new apply\FormIncompleteException(['status'], 'required');
            // Grad subform is for applicants who status with 卒業生
            elseif (($this->status == 2) && empty($this->gradSub))
                throw new apply\FormIncompleteException(['grad sub form'], 'applying grad related document');
            if (empty($this->dob))
                throw new apply\FormIncompleteException(['date of birth'], 'required');
            #endregion

            $data['st'] = $this->status;
            $data['db'] = $this->dob;
            $data['pp'] = $this->purpose;

            #region data check
            $doc = [];
            $lan = [];

            // check eng applicable
            for ($id = 1; $id <= 4; $id++) {
                if (!empty($this->document[$id])) {
                    // want eng
                    if (isset($this->language) && $this->language[$id]) {
                        // missing eng name
                        if (empty($this->enName))
                            throw new apply\FormIncompleteException(['english name'], 'applying english version');

                        $lan[$id] = true;
                        if (empty($data['en'])) $data['en'] = $this->enName;
                    }

                    $doc[$id] = $this->document[$id];
                }
            }

            if (!empty($this->document[5])) {
                $doc[5] = 1;
            }

            if (!empty($this->document[6])) {
                if (empty($this->interSub)) {
                    throw new apply\FormIncompleteException(['itl sub form'], 'applying itl related document');
                }

                $doc[6] = 1;
            }

            if (!empty($this->document[7])) {
                $doc[7] = $this->document[7];
            }
            #endregion

            $data['dc'] = $doc;
            if (!empty($lan)) $data['lg'] = $lan;

            #region Sub form
            if (isset($this->interSub))
                $data['is'] = $this->interSub->serialize();
            if (isset($this->gradSub))
                $data['gs'] = $this->gradSub->serialize();
            #endregion

            return Helper::json_stringify($data);
        }

        /**
         * Deserialize form data json string into form data, which must be serialized with DocIssue serialize function.
         *
         * @param string $json Serialized form data with DocIssue serialized function.
         * @throws JsonException
         */
        public function deserialize(string $json)
        {
            $data = Helper::json_parse($json);
            parent::Deserialize(Helper::json_stringify($data['bc']));

            $this->status = $data['st'];
            $this->dob = $data['db'];
            $this->purpose = $data['pp'];

            // omittable
            $this->enName = $data['en'] ?? '';

            for ($i = 1; $i <= 7; $i++) {
                $this->document[$i] = $data['dc']['$i'] ?? 0;
            }

            for ($i = 1; $i <= 4; $i++) {
                $this->language[$i] = $data['lg']['$i'] ?? 0;
            }

            if (isset($data['gs'])) {
                $this->gradSub = GraduatesSubForm::Deserialize(Helper::json_stringify($data['gs']));
            }

            if (isset($data['is'])) {
                $this->interSub = ResultAttendanceSubForm::Deserialize(Helper::json_stringify($data['is']));
            }
        }

        #region Validation
        /**
         * Check whether doc issue form data is valid.
         *
         * @throws apply\FormIncompleteException throw when required field missing.
         * @throws JsonException throw when supplied common field data unable to be parsed.
         */
        public static function validate(array $data): bool
        {
            // check if required field is set
            if (
                !(isset($data['bc']) &&
                    isset($data['db']) &&
                    isset($data['st']) &&
                    isset($data['pp']) &&
                    isset($data['dc']) && !empty($data['dc']))
            ) {
                throw new apply\FormIncompleteException(['bc', 'db', 'st', 'pp', 'dc'], 'required');
            }

            return parent::validate($data['bc'])
                && self::valid_db($data)
                && self::valid_st($data)
                && self::valid_pp($data)
                && self::valid_dc($data)
                && self::valid_en($data)
                && self::valid_lg($data)
                && self::valid_gs($data)
                && self::valid_is($data);
        }

        private static function valid_db(array $data): bool
        {
            // field should be a date;
            // and earlier then 6 years ago (at lease 6 yr old).
            return valid::validate_date($data['db']) &&
                $data['db'] < (time() - 189345600);
        }

        private static function valid_st(array $data): bool
        {
            return $data['st'] >= 1 && $data['st'] <= 4;
        }

        private static function valid_pp(array $data): bool
        {
            return $data['pp'] >= 1 && $data['pp'] <= 4;
        }

        private static function valid_dc(array $data): bool
        {
            $ret = true;

            for ($i = 1; $i <= 7; $i++) {
                if (isset($data[$i])) {
                    $ret &= (is_numeric($data[$i]) && $data[$i] >= 0);
                }
            }

            return $ret;
        }

        private static function valid_en(array $data): bool
        {
            // field is omittable, but should match format if set.
            return isset($data['en']) ?
                preg_match('/^([A-Za-z]|\s)+$/', $data['en']) :
                true;
        }

        private static function valid_lg(array $data): bool
        {
            // if not set, that is omitted, which is omittable.
            if (!isset($data['lg'])) {
                return true;
            }
            // if set, which could not be empty.
            else if (empty($data['lg'])) {
                return false;
            }

            $ret = true;

            for ($i = 1; $i <= 4; $i++) {
                if (isset($data['lg'][$i])) {
                    $ret &= is_bool($data['lg'][$i]);
                }
            }

            return $ret;
        }

        /**
         * Check whether applicant applied for doc type 3 or 4. If so, then check if sub form json is valid.
         *
         * @return boolean
         * @throws apply\FormIncompleteException throw when doc 3 or 4 is set, but `gs` was not set.
         */
        private static function valid_gs(array $data): bool
        {
            // Grad subform is for applicants who status with 卒業生 only
            // if so, then carry on checks.
            if ($data['st'] != 2) {
                return true;
            }

            return isset($data['gs']);
        }

        /**
         * Check whether applicant applied for doc type 6. If so, then check if sub form json is valid.
         *
         * @return boolean
         * @throws apply\FormIncompleteException throw when doc 6 is set, but `is` was not set.
         */
        private static function valid_is(array $data): bool
        {
            // if not set, that is omitted, which is omittable
            if (!isset($data['dc'][6])) {
                return true;
            }
            // if set, which could not be less then 1
            else if ($data['dc'][6] < 1) {
                return false;
            }

            return isset($data['is']);
        }
        #endregion
        #endregion
    }
}
