import React from "react";
import { useForm } from "react-hook-form";

import { DocIssue as Fields } from "../../../model/form";

interface Props {
    /**
     * Submit handler of this form.
     * @param data form data.
     */
    submit?(data: Fields): void;
    /**
     * Prefilled data.
     */
    data?: Fields;
}

export default function MainForm(props: Props) {
    const { register, handleSubmit, errors, watch } = useForm<Fields>({
        defaultValues: props.data,
    });

    let fieldTag = (error: any) => (error ? "error " : "") + "field";

    let docError = (): boolean => !watch("dc")?.some((value) => value > 0);

    return (
        <form
            className="ui form"
            onSubmit={props.submit ? handleSubmit(props.submit) : undefined}
        >
            <div className="field">
                <div className="two fields">
                    <div className={"required " + fieldTag(errors?.st)}>
                        <label>身分</label>
                        <select
                            className="ui dropdown"
                            name="st"
                            ref={register({
                                pattern: {
                                    value: /^[1-4]$/,
                                    message: "申請者の身分を選んでください",
                                },
                            })}
                            defaultValue="1"
                        >
                            <option value="1">在校生</option>
                            <option value="2">卒業生</option>
                            <option value="3">休学者</option>
                            <option value="4">退学者</option>
                        </select>
                    </div>
                    <div className={"required " + fieldTag(errors?.pp)}>
                        <label>用途</label>
                        <select
                            className="ui dropdown"
                            name="pp"
                            ref={register({
                                pattern: {
                                    value: /^[1-4]$/,
                                    message: "用途を選んでください",
                                },
                            })}
                            defaultValue="0"
                        >
                            <option value="0" disabled>
                                用途を選んでください
                            </option>
                            <option value="1">進学</option>
                            <option value="2">国家試験</option>
                            <option value="3">Visa申請手続</option>
                            <option value="4">旅行</option>
                        </select>
                    </div>
                </div>
            </div>
            {(errors?.st || errors?.pp) && (
                <p>{errors.pp?.message || errors.st?.message}</p>
            )}
            <div className={"required " + fieldTag(errors?.db)}>
                <label>生年月日</label>
                <input
                    type="date"
                    name="db"
                    placeholder="生年月日"
                    autoComplete="bday"
                    ref={register({
                        required: "生年月日は必須です",
                    })}
                />
            </div>
            {errors?.db && <p>{errors.db.message}</p>}
            <div
                className={fieldTag(errors?.en)}
                data-tooltip="申請者の氏名の英語表記、英文証明書を希望する場合のみ必須"
                data-position="top right"
            >
                <label>英語名</label>
                <input
                    type="text"
                    name="en"
                    placeholder="英語名"
                    ref={register({
                        required: watch("e"),
                        pattern: {
                            value: /^[A-Za-z]+(\s[A-Za-z]+)*\s*$/,
                            message: "英語名はローマ字で記入してください",
                        },
                    })}
                />
            </div>
            {errors?.en && (
                <p>
                    {errors.en.type == "required"
                        ? "英語で申し込む場合は英語名が必須にまります"
                        : errors.en.message}
                </p>
            )}
            <div className="field">
                <div className="ui checkbox">
                    <input
                        type="checkbox"
                        name="lg[0]"
                        ref={register()}
                        value="1"
                    />
                    <label>英語で申し込む</label>
                </div>
            </div>
            <div
                className="required field"
                data-tooltip="書類の数量を記入してください、一部は一通しか申し込めません"
                data-position="top right"
            >
                <label>書類</label>
                <div className="two fields">
                    <div className="field">
                        <label>在学証明書</label>
                        <input
                            type="number"
                            name="dc[0]"
                            ref={register({
                                min: {
                                    value: 0,
                                    message: "証明書の数はマイナスにできません",
                                },
                                required: docError(),
                            })}
                        />
                    </div>
                    <div className="field">
                        <label>成績証明書</label>
                        <input
                            type="number"
                            name="dc[1]"
                            ref={register({
                                min: {
                                    value: 0,
                                    message: "証明書の数はマイナスにできません",
                                },
                                required: docError(),
                            })}
                        />
                    </div>
                </div>
                <div className="two fields">
                    <div className="field">
                        <label>卒業証明書</label>
                        <input
                            type="number"
                            name="dc[2]"
                            ref={register({
                                min: {
                                    value: 0,
                                    message: "証明書の数はマイナスにできません",
                                },
                                required: docError(),
                            })}
                        />
                    </div>
                    <div className="field">
                        <label>卒業見込証明書</label>
                        <input
                            type="number"
                            name="dc[3]"
                            ref={register({
                                min: {
                                    value: 0,
                                    message: "証明書の数はマイナスにできません",
                                },
                                required: docError(),
                            })}
                        />
                    </div>
                </div>
                <div
                    className="fields"
                    style={{ justifyContent: "space-between" }}
                >
                    <div className="field">
                        <div className="ui checkbox">
                            <input
                                type="checkbox"
                                name="dc[4]"
                                ref={register({ required: docError() })}
                                value="1"
                            />
                            <label>勤労学生控除に関する証明書</label>
                        </div>
                    </div>
                    <div className="field">
                        <div className="ui checkbox">
                            <input
                                type="checkbox"
                                name="dc[5]"
                                ref={register({ required: docError() })}
                                value="1"
                            />
                            <label>所属機関フォーム</label>
                        </div>
                    </div>
                    <div className="field">
                        <div className="ui checkbox">
                            <input
                                type="checkbox"
                                name="dc[6]"
                                ref={register({ required: docError() })}
                                value="1"
                            />
                            <label>留学生学業成績おとび出席状況調書</label>
                        </div>
                    </div>
                </div>
            </div>
            {docError() && (
                <p style={{ color: "#db2828" }}>
                    {"書類を少なくとも1通を申し込んでください"}
                </p>
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
