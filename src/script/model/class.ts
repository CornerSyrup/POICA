import { default as R } from "./respond";

export interface Response extends R {
    list: Array<ResponseItem>;
}

export interface ResponseItem {
    /**
     * Class code.
     */
    code: string;
    /**
     * Attend number.
     */
    attend: number;
    /**
     * Total students of class.
     */
    total: number;
}
