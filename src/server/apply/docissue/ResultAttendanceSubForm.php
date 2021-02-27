<?php

namespace POICA\apply\docissue {

    use POICA\model\Helper;

    /**
     * Represent the sub form of result and attendance of international student.
     */
    class ResultAttendanceSubForm
    {
        #region fields
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
        #endregion

        #region methods
        /**
         * Serialize form data into json,
         * which must be deserialize with ResultAttendanceSubForm deserialize function.
         */
        public function serialize(): array
        {
            return [
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
        }

        /**
         * Deserialize form data json string into form data,
         * which must be serialized with ResultAttendanceSubForm serialize function.
         */
        public static function deserialize(string $json): ResultAttendanceSubForm
        {
            $data = Helper::json_parse($json);
            $ra = new ResultAttendanceSubForm();

            $ra->address = $data['ar'];
            $ra->nation = $data['na'];
            $ra->residentCard = $data['rc'];
            $ra->gender = $data['gn'];
            $ra->status = $data['st'];
            $ra->immigrantDate = $data['id'] ?? 0;
            $ra->admissionDate = $data['ad'] ?? 0;
            $ra->expireOfStay = $data['es'] ?? 0;
            $ra->expGradDate = $data['gd'] ?? 0;

            return $ra;
        }
        #endregion
    }
}
