import React from "react";
import { Link, RouteComponentProps } from "react-router-dom";
import { useForm } from "react-hook-form";

import Response from "../../model/response";

//#region form
interface Fields {
    usr: string;
    pwd: string;
    jfn: string;
    jln: string;
    jfk?: string;
    jlk?: string;
    con: boolean;
}

function SignUpForm(props: { submit(data: Fields): void; waiting: boolean }) {
    const { register, handleSubmit, errors, watch } = useForm<Fields>();

    let fieldTag = (error: any) => (error ? "error " : "") + "field";

    let kanaReq = (): boolean => watch("usr")?.length == 5;

    return (
        <form className="ui form" onSubmit={handleSubmit(props.submit)}>
            <div
                className={"required " + fieldTag(errors?.usr)}
                data-tooltip="数字5文字の学籍番号か数字6文字の教員番号"
                data-position="top right"
            >
                <label htmlFor="usr">学籍番号か教員番号</label>
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
            </div>
            <p>{errors?.usr && errors.usr.message}</p>
            <div
                className={"required " + fieldTag(errors?.pwd)}
                data-tooltip="最小8文字のパスワード"
                data-position="top right"
            >
                <label htmlFor="pwd">パスワード</label>
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
            </div>
            <p>{errors?.pwd && errors.pwd.message}</p>
            <div className={"required " + fieldTag(errors?.jfn && errors?.jln)}>
                <label htmlFor="jfn">氏名</label>
                <div className="two fields">
                    <div className={fieldTag(errors?.jln)}>
                        <input
                            name="jln"
                            type="text"
                            placeholder="苗字"
                            autoComplete="family-name"
                            ref={register({
                                required: "苗字を入力してください",
                            })}
                        />
                    </div>
                    <div className={fieldTag(errors?.jfn)}>
                        <input
                            name="jfn"
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
                {(errors?.jfn || errors?.jln) &&
                    (errors.jfn?.message || errors.jln?.message)}
            </p>
            <div className={"required " + fieldTag(errors?.jfk && errors?.jlk)}>
                <label htmlFor="jfk">フリガナ</label>
                <div className="two fields">
                    <div className={fieldTag(errors?.jlk)}>
                        <input
                            name="jlk"
                            type="text"
                            placeholder="フリガナ (苗字)"
                            ref={register({
                                required:
                                    kanaReq() &&
                                    "苗字のフリガナを入力してください",
                            })}
                        />
                    </div>
                    <div className={fieldTag(errors?.jfk)}>
                        <input
                            name="jfk"
                            type="text"
                            placeholder="フリガナ (名前)"
                            ref={register({
                                required:
                                    kanaReq() &&
                                    "名前のフリガナを入力してください",
                            })}
                        />
                    </div>
                </div>
            </div>
            <p>
                {(errors?.jfk || errors?.jlk) &&
                    (errors.jfk?.message || errors.jlk?.message)}
            </p>
            <div className={"required " + fieldTag(errors?.con)}>
                <div className="ui checkbox">
                    <input
                        name="con"
                        type="checkbox"
                        ref={register({
                            required: "利用規約を同意してください",
                        })}
                    />
                    <label htmlFor="con">
                        <a>利用規約</a>を同意します
                    </label>
                </div>
            </div>
            <p>
                すでに登録済みですか? <Link to="/signin/">サインイン</Link>
            </p>
            <div className="field">
                <button
                    className={`ui right floated circular small primary ${
                        props.waiting ? "loading " : ""
                    }button`}
                    type="submit"
                    disabled={props.waiting}
                >
                    登録する
                </button>
            </div>
        </form>
    );
}
//#endregion

//#region message
function Message(props: { status: number }) {
    let header: string = "";
    let content: JSX.Element = <React.Fragment />;

    switch (props.status) {
        case 0:
            header = "登録できません";
            content = (
                <p>
                    <a>システム管理者に連絡</a>
                    してください。
                </p>
            );
            break;
        case -1:
        case 1:
            return <React.Fragment />;
        case 13:
            header = "不正なデータが入力されています";
            break;
        case 21:
            header = "このアカウントは登録済みです";
            content = (
                <p>
                    登録したことがなければ、<a>システム管理者に連絡</a>
                    してください。
                </p>
            );
            break;
        case 10:
        case 11:
        case 12:
        default:
            header = "しばらくお待ちして、もう一度試してみてください";
            break;
    }

    return (
        <div className="ui negative message">
            <span className="header">{header}</span>
            {content}
        </div>
    );
}
//#endregion

interface Props extends RouteComponentProps {}
interface State {
    response: number;
    formWait: boolean;
}

export default class SignUp extends React.Component<Props, State> {
    componentDidMount = () => {
        document.title = "サインアップ";
    };

    constructor(props: Props) {
        super(props);

        this.state = {
            response: -1,
            formWait: false,
        };
    }

    submitSignUp = (data: Fields) => {
        this.setState({
            formWait: true,
        });

        fetch("/signup/", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data),
        })
            .then((r) => r.json())
            .then((response: Response) => {
                this.setState({
                    response: response.status,
                });

                if (response.status == 1) {
                    this.props.history.push("/signin/");
                }
            })
            .finally(() => {
                this.setState({
                    formWait: false,
                });
            });
    };

    render() {
        return (
            <React.Fragment>
                <Message status={this.state.response} />
                <SignUpForm
                    submit={this.submitSignUp}
                    waiting={this.state.formWait}
                />
            </React.Fragment>
        );
    }
}
