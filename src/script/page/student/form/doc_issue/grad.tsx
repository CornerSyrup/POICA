import React from "react";
import { useForm } from "react-hook-form";

import { DocIssue_Grad as Fields } from "../../../../model/form_fields";
import { Department } from "../../../../model/department";

interface Props {
    /**
     * Submit handler of this form.
     * @param data form data.
     */
    submit(data: Fields): void;
    /**
     * List of departments.
     */
    depts: Array<Department>;
    /**
     * Prefilled data.
     */
    data?: Fields;
}

export default function GradForm(props: Props) {
    const { register, handleSubmit, errors } = useForm<Fields>({
        defaultValues: props.data,
    });

    let now = new Date();

    let fieldTag = (error: any) => (error ? "error " : "") + "field";

    return (
        <form className="ui form" onSubmit={handleSubmit(props.submit)}>
            <div className={"required " + fieldTag(errors?.dp)}>
                <label>卒業学科</label>
                <select
                    className="ui dropdown"
                    name="dp"
                    ref={register({
                        pattern: {
                            value: /^\w{2}$/,
                            message: "卒業学科を選んでください",
                        },
                    })}
                    defaultValue="def"
                >
                    <option value="def" disabled>
                        卒業学科を選んでください
                    </option>
                    {props.depts.map((d: Department) => (
                        <option value={d.attr} key={d.attr}>
                            {d.full}
                        </option>
                    ))}
                </select>
            </div>
            {errors?.dp && <p>{errors.dp.message}</p>}
            <div className={"required " + fieldTag(errors?.gy || errors?.gm)}>
                <label>卒業年月</label>
                <div className="two fields">
                    <div
                        className={
                            "ui right labeled input " + fieldTag(errors?.gy)
                        }
                    >
                        <input
                            type="text"
                            name="gy"
                            inputMode="numeric"
                            placeholder="卒業年"
                            ref={register({
                                required: "卒業年を入力してください",
                                min: {
                                    value: now.getFullYear() - 5,
                                    message: "卒業年は5年前まで受け入れます",
                                },
                                max: {
                                    value: now.getFullYear() + 2,
                                    message: "卒業年は2年後まで受け入れます",
                                },
                                pattern: {
                                    value: /^\d{4}$/,
                                    message:
                                        "卒業年を数字4文字で入力してください",
                                },
                            })}
                        />
                        <label className="ui label">年</label>
                    </div>
                    <div
                        className={
                            "ui right labeled input " + fieldTag(errors?.gm)
                        }
                    >
                        <input
                            type="text"
                            name="gm"
                            inputMode="numeric"
                            placeholder="卒業月"
                            ref={register({
                                required: "卒業月を入力してください",
                                min: {
                                    value: 1,
                                    message: "卒業月を正しく入力してください",
                                },
                                max: {
                                    value: 12,
                                    message: "卒業月を正しく入力してください",
                                },
                                pattern: {
                                    value: /^\d{1,2}$/,
                                    message: "卒業月を数字で入力してください",
                                },
                            })}
                        />
                        <label className="ui label">月</label>
                    </div>
                </div>
            </div>
            {(errors?.gy || errors?.gm) && (
                <p>{errors.gy?.message || errors.gm?.message}</p>
            )}
            <div className={"required " + fieldTag(errors?.tn)}>
                <label>電話番号</label>
                <input
                    type="tel"
                    name="tn"
                    placeholder="0333441010"
                    autoComplete="tel"
                    ref={register({
                        required: "電話番号を入力してください",
                        maxLength: {
                            value: 10,
                            message: "先頭の0抜きで10文字で記入してください",
                        },
                        pattern: {
                            value: /^\d{10}$/,
                            message: "電話番号はハイフン抜きで入力してください",
                        },
                    })}
                />
            </div>
            {errors?.tn && <p>{errors.tn.message}</p>}
            <div className={"required " + fieldTag(errors?.pc || errors?.ad)}>
                <label>現住所</label>
                <div className="fields">
                    <div className={"four wide " + fieldTag(errors?.pc)}>
                        <div className={"ui labeled input"}>
                            <label className="ui label">〒</label>
                            <input
                                type="text"
                                name="pc"
                                placeholder="1600023"
                                inputMode="numeric"
                                ref={register({
                                    required: "郵便番号を入力してください",
                                    pattern: {
                                        value: /^\d{7}$/,
                                        message:
                                            "郵便番号はハイフン抜きで入力してください",
                                    },
                                })}
                            />
                        </div>
                    </div>
                    <div className={"twelve wide " + fieldTag(errors?.ad)}>
                        <input
                            type="text"
                            name="ad"
                            placeholder="東京都新宿区西新宿1-7-3"
                            ref={register({
                                required: "住所を入力してください",
                            })}
                        />
                    </div>
                </div>
            </div>
            {(errors?.pc || errors?.ad) && (
                <p>{errors.ad?.message || errors.pc?.message}</p>
            )}
            <div className="field">
                <input
                    className="ui primary button"
                    type="submit"
                    value="次へ"
                />
            </div>
        </form>
    );
}
