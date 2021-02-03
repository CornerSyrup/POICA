import React from "react";
import { useForm } from "react-hook-form";

import { CommonFields as Common } from "../../../model/form_fields";
import { Teacher } from "../../../model/teacher";

interface Props {
    submit(data: Common): void;
    teachers: Array<Teacher>;
    data?: Common;
}

export default function Common(props: Props) {
    const { register, handleSubmit, errors } = useForm<Common>({
        defaultValues: props.data,
    });

    let fieldTag = (error: any) => {
        return (error ? "error " : "") + "field";
    };

    return (
        <form
            className="ui ten wide column centered form"
            onSubmit={handleSubmit(props.submit)}
        >
            <div className={"required " + fieldTag(errors?.fn && errors?.ln)}>
                <label htmlFor="fn">氏名</label>
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
            <p>
                {(errors?.fn || errors?.ln) &&
                    (errors.ln?.message || errors.fn?.message)}
            </p>
            <div className="required field">
                <label htmlFor="fk">フリガナ</label>
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
            <p>
                {(errors?.fk || errors?.lk) &&
                    (errors.lk?.message || errors.fk?.message)}
            </p>
            <div className={"required " + fieldTag(errors?.si)}>
                <label htmlFor="si">学籍番号</label>
                <input
                    name="si"
                    type="number"
                    placeholder="学籍番号"
                    ref={register({
                        required: "学籍番号を入力してください",
                        pattern: {
                            value: /^\d{5}$/,
                            message: "学籍番号を正しく入力してください",
                        },
                    })}
                />
            </div>
            <p>{errors?.si && errors.si.message}</p>
            <div className={"required " + fieldTag(errors?.cc)}>
                <label htmlFor="cc">クラス記号</label>
                <input
                    name="cc"
                    type="text"
                    placeholder="クラス記号"
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
            <p>{errors?.cc && errors.cc.message}</p>
            <div className={"required " + fieldTag(errors?.ct)}>
                <label htmlFor="ct">担任教師</label>
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
            <p>{errors?.ct && errors.ct.message}</p>
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
