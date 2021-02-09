export default interface Respond {
    /**
     * Status code.
     */
    status: number;
}

export interface FormCatalogueResponse extends Respond {
    cat: Array<FormCatalogueItem>;
}

export interface FormCatalogueItem {
    id: number;
    type: string;
    status: string | number;
    date: Date;
}
