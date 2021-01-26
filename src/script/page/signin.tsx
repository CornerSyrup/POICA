import React from "react";
import { Link } from "react-router-dom";
import { useForm } from "react-hook-form";

//#region form
interface Fields {
    usr: string;
    pwd: string;
}

interface FormProps {
    submit(data: Fields): void;
    waiting: boolean;
}

function SignInForm(props: FormProps) {
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
                >
                    サインイン
                </button>
            </div>
        </form>
    );
}
//#endregion

interface SignInProps {}
interface SignInState {
    suica: boolean;
}

export default class SignIn extends React.Component<SignInProps, SignInState> {
    constructor(props: SignInProps) {
        super(props);
        this.state = {
            suica: false,
        };
    }

    componentDidMount = () => {
        document.title = "サインイン - IH12A Group 5";
    };

    formSignIn = (data: Fields) => {
        console.table(data);
    };

    suicaSignIn = () => {
        this.setState({
            suica: !this.state.suica,
        });
    };

    render() {
        return (
            <React.Fragment>
                <div className="ui negative message">
                    <span className="header">このアカウントは未登録です</span>
                    <p>
                        アカウントを<Link to="/signup/">新規登録</Link>
                        登録してください。
                    </p>
                </div>
                <SignInForm submit={this.formSignIn} />
                <div className="ui horizontal divider">OR</div>
                <button
                    className={
                        "ui circular fluid green button" +
                        (this.state.suica ? " loading" : "")
                    }
                    onClick={this.suicaSignIn}
                >
                    <div className="content">スイカでサインイン</div>
                </button>
            </React.Fragment>
        );
    }
}
