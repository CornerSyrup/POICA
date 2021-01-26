import React from "react";
import { Link } from "react-router-dom";
import { useForm } from "react-hook-form";

//#region form
interface Fields {
    usr: string;
    pwd: string;
    jfn: string;
    jln: string;
    jfk: string;
    jlk: string;
    con: boolean;
}

interface FormProps {
    submit(data: Fields): void;
}

function SignUpForm(props: FormProps) {
    const { register, handleSubmit, errors } = useForm<Fields>();

    return (
        <form className="ui form" onSubmit={handleSubmit(props.submit)}>
            <div
                className="field"
                data-tooltip="数字5文字の学籍番号か数字6文字の教員番号"
                data-position="top right"
            >
                <label htmlFor="usr">学籍番号か教員番号:</label>
                <input
                    name="usr"
                    type="text"
                    autoComplete="username"
                    ref={register({
                        required: "学籍番号か教員番号は必須です",
                        pattern: {
                            value: /^\d{5,6}$/,
                            message:
                                "数字5文字の学籍番号か数字6文字の教員番号を入力してください",
                        },
                    })}
                />
                {errors?.usr && errors.usr.message}
            </div>
            <div
                className="field"
                data-tooltip="最小8文字のパスワード"
                data-position="top right"
            >
                <label htmlFor="pwd">パスワード:</label>
                <input
                    name="pwd"
                    type="password"
                    autoComplete="new-password"
                    ref={register({
                        required: "パスワードは必須です",
                        minLength: {
                            value: 8,
                            message: "最低8文字必要",
                        },
                    })}
                />
                {errors?.pwd && errors.pwd.message}
            </div>
            <div className="field">
                <label htmlFor="jfn">氏名</label>
                <div className="two fields">
                    <div className="field">
                        <input
                            name="jfn"
                            type="text"
                            placeholder="苗字"
                            autoComplete="given-name"
                            ref={register({
                                required: "苗字を入力してください",
                            })}
                        />
                    </div>
                    <div className="field">
                        <input
                            name="jln"
                            type="text"
                            placeholder="名前"
                            autoComplete="family-name"
                            ref={register({
                                required: "名前を入力してください",
                            })}
                        />
                    </div>
                </div>
                {(errors?.jfn || errors?.jln) &&
                    (errors.jfn?.message || errors.jln?.message)}
            </div>
            <div className="field">
                <label htmlFor="jfk">フリガナ</label>
                <div className="two fields">
                    <div className="field">
                        <input
                            name="jfk"
                            type="text"
                            placeholder="フリガナ (苗字)"
                            ref={register({
                                required: "苗字のフリガナを入力してください",
                            })}
                        />
                    </div>
                    <div className="field">
                        <input
                            name="jlk"
                            type="text"
                            placeholder="フリガナ (名前)"
                            ref={register({
                                required: "名前のフリガナを入力してください",
                            })}
                        />
                    </div>
                </div>
                {(errors?.jfk || errors?.jlk) &&
                    (errors.jfk?.message || errors.jlk?.message)}
            </div>
            <div className="field">
                <div className="ui checkbox">
                    <input
                        className="ui checkbox"
                        name="con"
                        type="checkbox"
                        ref={register({
                            required: "利用規約を同意してください",
                        })}
                    />
                    <label>
                        <a>利用規約</a>を同意します
                    </label>
                </div>
            </div>
            <p>
                すでに登録済みですか? <Link to="/signin/">サインイン</Link>
            </p>
            <div className="field">
                <input
                    className="ui right floated circular small primary button"
                    type="submit"
                    value="登録する"
                />
            </div>
        </form>
    );
}
//#endregion

export default class SignUp extends React.Component {
    componentDidMount = () => {
        document.title = "サインアップ - IH12A Group 5";
    };

    submitSignUp = (data: Fields) => {
        console.table(data);
    };

    render() {
        return (
            <React.Fragment>
                <div className="ui negative message">
                    <span className="header">このアカウントは登録済みです</span>
                    <p>
                        登録したことがなければ、<a>システム管理者に連絡</a>
                        してください。
                    </p>
                </div>
                <SignUpForm submit={this.submitSignUp} />
            </React.Fragment>
        );
    }
}
