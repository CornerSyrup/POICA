export interface CommonFields {
    /**
     * First name, given name.
     */
    fn: string;
    /**
     * Fist name kana.
     */
    fk: string;
    /**
     * Last name, family name.
     */
    ln: string;
    /**
     * Last name kana.
     */
    lk: string;
    /**
     * Student ID.
     */
    si: number;
    /**
     * Class Code.
     */
    cc: string;
    /**
     * Class Teacher.
     */
    ct: number;
}

//#region DocIssue
export interface DocIssue {
    /**
     * Basic fields.
     */
    bc: CommonFields;
    /**
     * Date of birth. As unix timestamp.
     */
    db: number;
    /**
     * Status of the applicant.
     */
    st: number;
    /**
     * Purpose of apply.
     */
    pp: number;
    /**
     * Copies of documents to apply.
     */
    dc: Array<number>;
    /**
     * English name of the applicant, optional.
     */
    en?: string;
    /**
     * True for English, false for Japanese. Only 1~4 accepted.
     */
    lg: Array<boolean>;
    /**
     * Sub form for applicant who applied doc type 3, 4. JSON.
     */
    gs?: DocIssue_Grad;
    /**
     * Sub form for applicants who applied doc type 6. JSON.
     */
    is?: DocIssue_Intl;
}

export interface DocIssue_Grad {
    /**
     * Department which the applicant is graduated from, in abbr.
     */
    dp: string;
    /**
     * Year of graduation.
     */
    gy: number;
    /**
     * Month of graduation.
     */
    gm: number;
    /**
     * Postal code of the applicant's address.
     */
    pc: string;
    /**
     * Applicant's address.
     */
    ad: string;
    /**
     * Phone no. of to contact the applicant.
     */
    tn: string;
}

export interface DocIssue_Intl {
    /**
     * Applicant's address.
     */
    ar: string;
    /**
     * Nation of the applicant. ISO 3166-1.
     */
    na: string;
    /**
     * Card number of the resident card.
     */
    rc: string;
    /**
     * True for male, false for female.
     */
    gn: boolean;
    /**
     * Status of stay, default `student`.
     */
    st: string;
    /**
     * Immigrant data.
     */
    id: number;
    /**
     * Admission data.
     */
    ad: number;
    /**
     * Expiration date of stay.
     */
    es: number;
    /**
     * Expected graduation date.
     */
    gd: number;
}
//#endregion
