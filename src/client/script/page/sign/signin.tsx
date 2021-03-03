import React from "react";
import { Link, RouteComponentProps } from "react-router-dom";
import { useForm } from "react-hook-form";

import { default as ReadIDm } from "../../model/felica";
import { getSHA256 } from "../../model/hash";
import Response from "../../model/response";
import { HOST } from "../../model/server";

//#region form
interface Fields {
    usr: string;
    pwd: string;
}
function SignInForm(props: { submit(data: Fields): void; waiting: boolean }) {
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
            <p>
                アカウント持ってない? <Link to="/signup/">新規登録</Link>
            </p>
            <div className="field">
                <button
                    className={
                        "ui circular small primary button" +
                        (props.waiting ? " loading" : "")
                    }
                    type="submit"
                    disabled={props.waiting}
                >
                    サインイン
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
            header = "パスワードが誤っています";
            content = <p>ご確認のうえ、再度ご入力ください。</p>;
            break;
        case -1:
        case 1:
        case 2:
            return <React.Fragment />;
        case 21:
            header = "このアカウントは未登録です";
            content = (
                <p>
                    アカウントを<Link to="/signup/">新規登録</Link>
                    登録してください。
                </p>
            );
            break;
        case 22:
            header = "このスイカカードは未登録です";
            break;
        default:
            content = <p>もう一度試してみてください</p>;
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
    formWait: boolean;
    suicaWait: boolean;
    respond: number;
}

export default class SignIn extends React.Component<Props, State> {
    constructor(props: Props) {
        super(props);
        this.state = {
            suicaWait: false,
            formWait: false,
            respond: -1,
        };
    }

    componentDidMount = () => {
        document.title = "サインイン";
    };

    formSignIn = async (data: Fields) => {
        this.setState({
            formWait: true,
        });

        fetch("/signin/", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data),
        })
            .then((r) => r.json())
            .then((respond: Response) => {
                this.setState({
                    respond: respond.status,
                });

                if (respond.status == 1) {
                    this.redirect2Insider();
                }
            })
            .finally(() => {
                this.setState({
                    formWait: false,
                });
            });
    };

    suicaSignIn = async () => {
        this.setState({
            suicaWait: true,
        });

        let hash = await ReadIDm()
            .then((c) => getSHA256(c))
            .catch(() => {
                this.setState({ suicaWait: false });
            });

        fetch("/signin/suica/", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ idm: hash }),
        })
            .then((r) => r.json())
            .then((response: Response) => {
                this.setState({
                    respond: response.status,
                });

                if (response.status == 2) {
                    this.redirect2Insider();
                }
            })
            .finally(() => {
                this.setState({
                    suicaWait: false,
                });
            });
    };

    redirect2Insider = () => {
        window.location.href = `http://${HOST}`;
    };

    render() {
        return (
            <React.Fragment>
                <Message status={this.state.respond} />
                <SignInForm
                    submit={this.formSignIn}
                    waiting={this.state.formWait}
                />
                <div className="ui horizontal divider">OR</div>
                <button
                    className={
                        "ui circular fluid green button" +
                        (this.state.suicaWait ? " loading" : "")
                    }
                    onClick={this.suicaSignIn}
                >
                    <div className="content">スイカでサインイン</div>
                </button>
            </React.Fragment>
        );
    }
}
