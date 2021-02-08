export interface BasicStudent {
    sid: string;
    fname: string;
    lname: string;
}

export interface SuicaStudent extends BasicStudent {
    /**
     * IDm code of the suica card registered by the student.
     */
    suica: string;
}
