<?php

/**
 * Class represent the document issue application form, aka 証明書発行願.
 */

namespace model\app_form;

require_once 'model/apply/AppForm.php';

/**
 * Represent the application form of document issue application,
 * aka 証明書等発行願.
 */
class DocIssue extends AppForm
{
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

    /**
     * Sub form for international student only and when require.
     */
    public ResultAttendanceSubForm $interSub;
    /**
     * Sub form for graduates students who desired to apply graduation related documents
     */
    public GraduatesSubForm $gradSub;

    /**
     * Getter for date of birth.
     *
     * @return int
     */
    public function GetDateOfBirth(): int
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
    public function SetDateOfBirth(int $year, int $month, int $day): void
    {
        $this->dob = mktime(null, null, null, $month, $day, $year);
    }

    /**
     * Serialize form data into json, which must be deserialize with DocIssue deserialize function.
     *
     * @return string Serialized json as string.
     * @throws FormIncompleteException throw when some required field or sub-fom missing.
     */
    public function Serialize(): string
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

        if (empty($this->purpose)) {
            throw new FormIncompleteException('purpose');
        }
        if (empty($this->status)) {
            throw new FormIncompleteException('status');
        }
        if (empty($this->dob)) {
            throw new FormIncompleteException('date of birth');
        }

        $data['st'] = $this->status;
        $data['db'] = $this->dob;
        $data['pp'] = $this->purpose;

        #region apply doc data check
        $doc = array();
        $lan = array();

        // need doc 1
        if (!empty($this->document[1])) {
            // want eng ver
            if (isset($this->language) && $this->language[1]) {
                // missing eng name
                if (empty($this->enName)) {
                    throw new FormIncompleteException('english name', 'applying english version');;
                }

                $lan[1] = $this->language[1];
                $data['en'] = $this->enName;
            }

            $doc[1] = $this->document[1];
        }

        if (!empty($this->document[2])) {
            if (isset($this->language) && $this->language[2]) {
                if (empty($this->enName)) {
                    throw new FormIncompleteException('english name', 'applying english version');;
                }

                $lan[2] = $this->language[2];
                $data['en'] = $this->enName;
            }

            $doc[2] = $this->document[2];
        }

        if (!empty($this->document[3])) {
            if (empty($this->gradSub)) {
                throw new FormIncompleteException('grad sub form', 'applying grad related document');
            }

            if (isset($this->language) && $this->language[3]) {
                if (empty($this->enName)) {
                    throw new FormIncompleteException('english name', 'applying english version');;
                }

                $lan[3] = $this->language[3];
                $data['en'] = $this->enName;
            }

            $doc[3] = $this->document[3];
            $data['gs'] = $this->gradSub->Serialize();
        }

        if (!empty($this->document[4])) {
            if (empty($this->gradSub)) {
                throw new FormIncompleteException('english name', 'applying english version');;
            }

            if (isset($this->language) && $this->language[4]) {
                if (empty($this->enName)) {
                    throw new FormIncompleteException('grad sub form', 'applying grad related document');
                }

                $lan[4] = $this->language[4];
                $data['en'] = $this->enName;
            }

            $doc[4] = $this->document[4];
            $data['gs'] = $this->gradSub->Serialize();
        }

        if (!empty($this->document[5])) {
            $doc[5] = 1;
        }

        if (!empty($this->document[6])) {
            if (empty($this->interSub)) {
                throw new FormIncompleteException('itl sub form', 'applying itl related document');
            }

            $doc[6] = 1;
            $data['is'] = $this->interSub->Serialize();
        }

        if (!empty($this->document[7])) {
            $doc[7] = $this->document[7];
        }
        #endregion

        $data['dc'] = $doc;
        if (!empty($lan)) {
            $data['lg'] = $lan;
        }

        // base class
        $data['bc'] = parent::Serialize();

        return json_encode($data);
    }

    /**
     * Deserialize form data json string into form data, which must be serialized with DocIssue serialize function.
     *
     * @param string $json Serialized form data with DocIssue serialized function.
     */
    public function Deserialize(string $json)
    {
        $data = json_decode($json);
        parent::Deserialize($data->bc);

        $this->status = $data->st;
        $this->dob = $data->db;
        $this->purpose = $data->pp;

        // omittable
        $this->enName = $data->en ?? '';

        for ($i = 1; $i <= 7; $i++) {
            $this->document[$i] = $data->dc->$i ?? 0;
        }

        for ($i = 1; $i <= 4; $i++) {
            $this->language[$i] = $data->lg->$i ?? 0;
        }

        if (isset($data->gs)) {
            $this->gradSub = GraduatesSubForm::Deserialize($data->gs);
        }

        if (isset($data->is)) {
            $this->interSub = ResultAttendanceSubForm::Deserialize($data->is);
        }
    }
}

/**
 * Represent the sub form of result and attendance of international student,
 * aka 留学生学業成績および出席状況調書.
 */
class ResultAttendanceSubForm
{
    /**
     * Current living address.
     */
    public string $address;
    /**
     * Nation of the international student, in format of iso 3166-1.
     */
    public string $nation;
    /**
     * Resident card number.
     */
    public string $residentCard;
    /**
     * Gender of the applicant; true for male, false for female.
     */
    public bool $gender;
    /**
     * Status of stay, default as student.
     */
    public string $status = 'Student';
    /**
     * Date of immigrant.
     */
    public int $immigrantDate;
    /**
     * Date pf admission for schooling.
     */
    public int $admissionDate;
    /**
     * Date of expiration for the period of stay.
     */
    public int $expireOfStay;
    /**
     * Expected graduation date.
     */
    public int $expGradDate;

    /**
     * Serialize form data into json, which must be deserialize with ResultAttendanceSubForm deserialize function.
     *
     * @return string Serialized json as string.
     */
    public function Serialize(): string
    {
        $data = [
            'ar' => $this->address ?? '',
            'na' => $this->nation ?? '',
            'rc' => $this->residentCard ?? '',
            'gn' => $this->gender ?? false,
            'st' => $this->status,
            'id' => $this->immigrantDate ?? 0,
            'ad' => $this->admissionDate ?? 0,
            'es' => $this->expireOfStay ?? 0,
            'gd' => $this->expGradDate ?? 0
        ];

        return json_encode($data);
    }

    /**
     * Deserialize form data json string into form data, which must be serialized with ResultAttendanceSubForm serialize function.
     *
     * @param string $json Serialized form data with ResultAttendanceSubForm serialized function.
     * @return ResultAttendanceSubForm Form data in ResultAttendanceSubForm object.
     */
    public static function Deserialize(string $json): ResultAttendanceSubForm
    {
        $data = json_decode($json);
        $ra = new ResultAttendanceSubForm();

        $ra->address = $data->ar;
        $ra->nation = $data->na;
        $ra->residentCard = $data->rc;
        $ra->gender = $data->gn;
        $ra->status = $data->st;
        $ra->immigrantDate = $data->id ?? 0;
        $ra->admissionDate = $data->ad ?? 0;
        $ra->expireOfStay = $data->es ?? 0;
        $ra->expGradDate = $data->gd ?? 0;

        return $ra;
    }
}

/**
 * Represent the sub form for graduates in document issue form.
 */
class GraduatesSubForm
{
    /**
     * Department the applicant graduate from.
     */
    public string $department;
    /**
     * Year af the graduation.
     */
    public int $gradYear;
    /**
     * Month af the graduation.
     */
    public int $gradMonth;
    /**
     * Postal code of the applicant's address.
     */
    public string $postCode;
    /**
     * Address of the applicant in string.
     */
    public string $address;
    /**
     * Telephone number of the applicant.
     */
    public string $telNo;

    /**
     * Serialize form data into json, which must be deserialize with GraduatesSubForm deserialize function.
     *
     * @return string Serialized json as string.
     */
    public function Serialize(): string
    {
        $data = [

            'dp' => $this->department ?? '',
            'gy' => $this->gradYear ?? 0,
            'gm' => $this->gradMonth ?? 0,
            'pc' => $this->postCode ?? 0,
            'ad' => $this->address ?? '',
            'tn' => $this->telNo ?? 0
        ];

        return json_encode($data);
    }

    /**
     * Deserialize form data json string into form data, which must be serialized with GraduatesSubForm serialize function.
     *
     * @param string $json Serialized form data with GraduatesSubForm serialized function.
     * @return GraduatesSubForm Form data in GraduatesSubForm object.
     */
    public static function Deserialize(string $json): GraduatesSubForm
    {
        $data = json_decode($json);
        $gd = new GraduatesSubForm();

        $gd->department = $data->dp;
        $gd->gradYear = $data->gy;
        $gd->gradMonth = $data->gm;
        $gd->postCode = $data->pc;
        $gd->address = $data->ad;
        $gd->telNo = $data->tn;

        return $gd;
    }
}
