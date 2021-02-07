import React from "react";
import { useForm } from "react-hook-form";

import { DocIssue_Intl as Fields } from "../../../../model/form_fields";

interface Props {
    /**
     * Submit handler of this form.
     * @param data form data.
     */
    submit?(data: Fields): void;
    /**
     * List of countries, with key of code, and value of name.
     */
    countries: object;
    /**
     * Prefilled data.
     */
    data?: Fields;
}

export default function GradForm(props: Props) {
    const { register, handleSubmit, errors } = useForm<Fields>({
        defaultValues: props.data,
    });

    let fieldTag = (error: any) =>
        "required " + (error ? "error " : "") + "field";

    let monPref = (ev: React.ChangeEvent<HTMLInputElement>) => {
        let slt = document.querySelector("#pref") as HTMLSelectElement;
        let sp = ["東京都", "北海道", "京都府", "大阪府"];

        // full
        if (sp.some((pref) => pref == ev.target.value)) {
            ev.target.value = ev.target.value.substr(0, 2);
            slt.value = ev.target.value.substr(-1);
        }
        // some
        else if (sp.some((pref) => pref.substr(0, 2) == ev.target.value)) {
            slt.value =
                sp
                    .find((pref) => pref.substr(0, 2) == ev.target.value)
                    ?.substr(-1) || "県";
        } else {
            slt.value = "県";
        }
    };

    let cleanUp = (data: Fields) => {
        data.ar = ((data.ar as unknown) as Array<Array<string>>)
            ?.map((val) => val.join(""))
            .join(" ");

        data.gn = ((data.gn as unknown) as number) == 1;
        data.id = new Date(data.id).getTime() / 1000;
        data.ad = new Date(data.ad).getTime() / 1000;
        data.es = new Date(data.es).getTime() / 1000;
        data.gd = new Date(data.gd).getTime() / 1000;
        if (props.submit) props.submit(data);
    };

    return (
        <form className="ui form" onSubmit={handleSubmit(cleanUp)}>
            <div
                className={fieldTag(errors?.ar)}
                data-tooltip="本国の住所ではなく、日本国内の現住所"
                data-position="top right"
            >
                <label>現住所</label>
                <div className="three fields">
                    <div className="field">
                        <input
                            type="text"
                            name="ar[0][0]"
                            placeholder="都・道・府・県"
                            ref={register({
                                required: "都道府県を入力してください",
                            })}
                            onChange={monPref}
                        />
                        <select id="pref" name="ar[0][1]" ref={register}>
                            <option>都</option>
                            <option>道</option>
                            <option>府</option>
                            <option>県</option>
                        </select>
                    </div>
                    <div className="field">
                        <input
                            type="text"
                            name="ar[1][0]"
                            placeholder="市・郡"
                            ref={register({
                                required: "あを入力してください",
                            })}
                        />
                        <select name="ar[1][1]" ref={register}>
                            <option>市</option>
                            <option>郡</option>
                        </select>
                    </div>
                    <div className="field">
                        <input
                            type="text"
                            name="ar[2][0]"
                            placeholder="区・町・村"
                            ref={register({
                                required: "区画を入力してください",
                            })}
                        />
                        <select name="ar[2][1]" ref={register}>
                            <option>区</option>
                            <option>町</option>
                            <option>村</option>
                        </select>
                    </div>
                </div>
            </div>
            {errors?.ar && <p>{errors.ar.message}</p>}
            <div className={fieldTag(errors?.na)}>
                <label>国籍</label>
                <select
                    name="na"
                    defaultValue="0"
                    ref={register({
                        pattern: {
                            value: /^[A-Z]{2}$/,
                            message: "国籍を選んでください",
                        },
                    })}
                >
                    <option value="0" disabled>
                        国籍を選んでください
                    </option>
                    {Object.entries(props.countries).map((pair) => (
                        <option value={pair[0]} key={pair[0]}>
                            {pair[1]}
                        </option>
                    ))}
                </select>
            </div>
            {errors?.na && <p>{errors.na.message}</p>}
            <div className={fieldTag(errors?.gn)}>
                <label>性別</label>
                <select
                    name="gn"
                    defaultValue="def"
                    ref={register({
                        pattern: {
                            value: /^[0|1]$/,
                            message: "性別を選んでください",
                        },
                    })}
                >
                    <option value="def" disabled>
                        性別を選んでください
                    </option>
                    <option value="1">男性</option>
                    <option value="0">女性</option>
                </select>
            </div>
            {errors?.gn && <p>{errors.gn.message}</p>}
            <div className="field">
                <div className="fields">
                    <div className={"ten wide " + fieldTag(errors?.rc)}>
                        <label>在留カードNo.</label>
                        <input
                            type="text"
                            name="rc"
                            ref={register({
                                required: "在留カード番号を入力してください",
                                pattern: {
                                    value: /^\w{12}$/,
                                    message:
                                        "在留カード番号を12桁で入力してください",
                                },
                            })}
                        />
                    </div>
                    <div className={"six wide " + fieldTag(errors?.st)}>
                        <label>在留資格</label>
                        <select
                            name="st"
                            defaultValue="student"
                            ref={register({
                                required: "在留資格を選んでください",
                            })}
                        >
                            <option value="student">留学生</option>
                        </select>
                    </div>
                </div>
            </div>
            {(errors?.rc || errors?.st) && (
                <p>{errors.rc?.message || errors.st?.message}</p>
            )}
            <div className="field">
                <div className="two fields">
                    <div className={fieldTag(errors?.id)}>
                        <label>入国年月日</label>
                        <input
                            type="date"
                            name="id"
                            ref={register({
                                required: "入国年月日を入力してください",
                            })}
                        />
                    </div>
                    <div className={fieldTag(errors?.ad)}>
                        <label>入学年月日</label>
                        <input
                            type="date"
                            name="ad"
                            ref={register({
                                required: "入学年月日を入力してください",
                            })}
                        />
                    </div>
                </div>
            </div>
            {(errors?.id || errors?.ad) && (
                <p>{errors.id?.message || errors.ad?.message}</p>
            )}
            <div className="field">
                <div className="two fields">
                    <div className={fieldTag(errors?.es)}>
                        <label>在留期限</label>
                        <input
                            type="date"
                            name="es"
                            ref={register({
                                required: "在留期限を入力してください",
                            })}
                        />
                    </div>
                    <div className={fieldTag(errors?.gd)}>
                        <label>卒業予定日</label>
                        <input
                            type="date"
                            name="gd"
                            ref={register({
                                required: "卒業予定日を入力してください",
                            })}
                        />
                    </div>
                </div>
            </div>
            {(errors?.es || errors?.gd) && (
                <p> {errors.es?.message || errors.gd?.message} </p>
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
