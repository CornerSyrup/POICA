<?php

namespace POICA\apply\docissue {

    use POICA\model\Helper;

    /**
     * Represent the sub form for graduates in document issue form.
     */
    class GraduatesSubForm
    {
        #region fields
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
        #endregion

        #region methods
        /**
         * Serialize form data into json,
         * which must be deserialize with GraduatesSubForm deserialize function.
         */
        public function serialize(): array
        {
            return [
                'dp' => $this->department ?? '',
                'gy' => $this->gradYear ?? 0,
                'gm' => $this->gradMonth ?? 0,
                'pc' => $this->postCode ?? 0,
                'ad' => $this->address ?? '',
                'tn' => $this->telNo ?? 0
            ];
        }

        /**
         * Deserialize form data json string into form data,
         * which must be serialized with GraduatesSubForm serialize function.
         */
        public static function deserialize(string $json): GraduatesSubForm
        {
            $data = Helper::json_parse($json);
            $gd = new GraduatesSubForm();

            $gd->department = $data['dp'];
            $gd->gradYear = $data['gy'];
            $gd->gradMonth = $data['gm'];
            $gd->postCode = $data['pc'];
            $gd->address = $data['ad'];
            $gd->telNo = $data['tn'];

            return $gd;
        }
        #endregion
    }
}
