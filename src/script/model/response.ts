import { FormCatalogueItem } from "./form";
import { LessonItem } from "./lesson";
import { BriefStudent } from "./student";
import { Teacher } from "./teacher";

export default interface Response {
    /**
     * Status code.
     */
    status: number;
}

export interface FormCatalogueResponse extends Response {
    cat: Array<FormCatalogueItem>;
}

//#region Teacher API
export interface TeacherListResponse extends Response {
    teachers: Array<Teacher>;
}
//#endregion

//#region Lesson API
export interface LessonListResponse extends Response {
    lessons: Array<LessonItem>;
}
//#endregion

//#region Student API
export interface StudentListRespond extends Response {
    students: Array<BriefStudent>;
}
//#endregion
