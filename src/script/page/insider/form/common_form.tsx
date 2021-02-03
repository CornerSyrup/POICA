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

    return (
        <form
            className="ui ten wide column centered form"
            onSubmit={handleSubmit(props.submit)}
        >
            <div className="field">
                <label htmlFor="fn">氏名</label>
                <div className="two fields">
                    <div className="field">
                        <input
                            type="text"
                            name="ln"
                            placeholder="苗字"
                            autoComplete="family-name"
                            ref={register({
                                required: "苗字を入力してください",
                            })}
                            defaultValue="a"
                        />
                    </div>
                    <div className="field">
                        <input
                            name="fn"
                            type="text"
                            placeholder="名前"
                            autoComplete="given-name"
                            ref={register({
                                required: "名前を入力してください",
                            })}
                            defaultValue="a"
                        />
                    </div>
                </div>
                {(errors?.fn || errors?.ln) &&
                    (errors.ln?.message || errors.fn?.message)}
            </div>
            <div className="field">
                <label htmlFor="fk">フリガナ</label>
                <div className="two fields">
                    <div className="field">
                        <input
                            name="lk"
                            type="text"
                            placeholder="フリガナ (苗字)"
                            defaultValue="a"
                            ref={register({
                                required: "苗字のフリガナを入力してください",
                            })}
                        />
                    </div>
                    <div className="field">
                        <input
                            name="fk"
                            type="text"
                            placeholder="フリガナ (名前)"
                            defaultValue="a"
                            ref={register({
                                required: "名前のフリガナを入力してください",
                            })}
                        />
                    </div>
                </div>
                {(errors?.fk || errors?.lk) &&
                    (errors.lk?.message || errors.fk?.message)}
            </div>
            <div className="field">
                <label htmlFor="si">学籍番号</label>
                <input
                    name="si"
                    type="number"
                    placeholder="学籍番号"
                    defaultValue="12345"
                    ref={register({
                        required: "学籍番号を入力してください",
                        pattern: {
                            value: /^\d{5}$/,
                            message: "学籍番号を正しく入力してください",
                        },
                    })}
                />
                {errors?.si && errors.si.message}
            </div>
            <div className="field">
                <label htmlFor="cc">クラス記号</label>
                <input
                    name="cc"
                    type="text"
                    placeholder="クラス記号"
                    defaultValue="ih12a092"
                    ref={register({
                        required: "クラス記号を入力してください",
                        pattern: {
                            value: /^\w{2}\d{2}\w{1}\d{3}$/,
                            message:
                                "クラス記号を正しく入力してください（教室番号も含みます、IH12A092）",
                        },
                    })}
                />
                {errors?.cc && errors.cc.message}
            </div>
            <div className="field">
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
                {errors?.ct && errors.ct.message}
            </div>
            <div className="field">
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
            <div className="right floated field">
                <input
                    className="ui primary button"
                    type="submit"
                    value="次へ"
                />
            </div>
        </form>
    );
}
