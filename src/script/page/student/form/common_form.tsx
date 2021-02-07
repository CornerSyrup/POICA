import React from "react";
import { useForm } from "react-hook-form";

import { CommonFields as Common } from "../../../model/form_fields";
import { Teacher } from "../../../model/teacher";

interface Props {
    submit?(data: Common): void;
    teachers: Array<Teacher>;
    data?: Common;
}

export default function Common(props: Props) {
    const { register, handleSubmit, errors } = useForm<Common>({
        defaultValues: props.data,
    });

    let fieldTag = (error: any) => (error ? "error " : "") + "field";

    return (
        <form
            className="ui form"
            onSubmit={props.submit ? handleSubmit(props.submit) : undefined}
        >
            <div className={"required " + fieldTag(errors?.fn && errors?.ln)}>
                <label>氏名</label>
                <div className="two fields">
                    <div className={fieldTag(errors?.ln)}>
                        <input
                            type="text"
                            name="ln"
                            placeholder="苗字"
                            autoComplete="family-name"
                            ref={register({
                                required: "苗字を入力してください",
                            })}
                        />
                    </div>
                    <div className={fieldTag(errors?.fn)}>
                        <input
                            name="fn"
                            type="text"
                            placeholder="名前"
                            autoComplete="given-name"
                            ref={register({
                                required: "名前を入力してください",
                            })}
                        />
                    </div>
                </div>
            </div>
            {(errors?.fn || errors?.ln) && (
                <p>{errors.ln?.message || errors.fn?.message}</p>
            )}
            <div className="required field">
                <label>フリガナ</label>
                <div className="two fields">
                    <div className={fieldTag(errors?.lk)}>
                        <input
                            name="lk"
                            type="text"
                            placeholder="フリガナ (苗字)"
                            ref={register({
                                required: "苗字のフリガナを入力してください",
                            })}
                        />
                    </div>
                    <div className={fieldTag(errors?.fk)}>
                        <input
                            name="fk"
                            type="text"
                            placeholder="フリガナ (名前)"
                            ref={register({
                                required: "名前のフリガナを入力してください",
                            })}
                        />
                    </div>
                </div>
            </div>
            {(errors?.fk || errors?.lk) && (
                <p>{errors.lk?.message || errors.fk?.message}</p>
            )}
            <div className="field">
                <div className="two fields">
                    <div className={"required " + fieldTag(errors?.si)}>
                        <label>学籍番号</label>
                        <input
                            name="si"
                            type="text"
                            placeholder="学籍番号"
                            inputMode="numeric"
                            ref={register({
                                required: "学籍番号を入力してください",
                                pattern: {
                                    value: /^\d{5}$/,
                                    message: "学籍番号を正しく入力してください",
                                },
                            })}
                        />
                    </div>
                    <div className={"required " + fieldTag(errors?.cc)}>
                        <label>クラス記号</label>
                        <input
                            name="cc"
                            type="text"
                            placeholder="ih12a092"
                            ref={register({
                                required: "クラス記号を入力してください",
                                pattern: {
                                    value: /^\w{2}\d{2}\w{1}\d{3}$/,
                                    message:
                                        "クラス記号を正しく入力してください（教室番号も含みます、IH12A092）",
                                },
                            })}
                        />
                    </div>
                </div>
            </div>
            {(errors?.si || errors?.cc) && (
                <p>{errors.cc?.message || errors.si?.message}</p>
            )}
            <div className={"required " + fieldTag(errors?.ct)}>
                <label>担任教師</label>
                <select
                    className="ui dropdown"
                    name="ct"
                    ref={register({
                        pattern: {
                            value: /\d{6}/,
                            message: "担任教師を選んでください",
                        },
                    })}
                    defaultValue="def"
                >
                    <option value="def" key={0} disabled>
                        担任教師を選んでください
                    </option>
                    {props.teachers.map((t: Teacher) => (
                        <option value={t.tid} key={t.tid}>
                            {`${t.lname} ${t.fname}`}
                        </option>
                    ))}
                </select>
            </div>
            {errors?.ct && <p>{errors.ct.message}</p>}
            <div className="required field">
                <div className="ui checkbox">
                    <input
                        type="checkbox"
                        name="con"
                        ref={register({
                            required: true,
                        })}
                    />
                    <label>
                        <a>利用規約</a>を同意します
                    </label>
                </div>
            </div>
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
