import React from "react";

import ReadIDm from "../../model/felica";
import { getSHA256 } from "../../model/hash";
import Response from "../../model/response";
import { Fetch } from "../../model/server";

interface Props {}
interface State {
    regWait: boolean;
    deregWait: boolean;
    stateText: string;
}

export default class Suica extends React.Component<Props, State> {
    componentDidMount = () => {
        document.title = "アカウント設定";
    };

    constructor(props: Props) {
        super(props);

        this.state = {
            regWait: false,
            deregWait: false,
            stateText: "",
        };
    }

    registerSuica = async () => {
        this.setState({
            regWait: true,
            stateText: "スイカカード登録中...",
        });

        let hash = await ReadIDm()
            .then((c) => getSHA256(c))
            .catch(() => {
                this.setState({
                    regWait: false,
                    stateText: "スイカ登録ができませんでした",
                });
            });

        Fetch("/apis/suica/", "POST", { idm: hash })
            .then((response: Response) => {
                let msg: string = "";

                switch (response.status) {
                    case 2:
                        msg = "スイカカード登録しました";
                        break;
                    case 11:
                        msg =
                            "サインアウトされました、もう一度サインインしてください";
                        break;
                    case 30:
                    default:
                        msg = "スイカカード登録できませんでした";
                        break;
                }

                this.setState({
                    stateText: msg,
                });
            })
            .finally(() => {
                this.setState({
                    regWait: false,
                });
            });
    };

    deregisterSuica = () => {
        this.setState({
            deregWait: true,
            stateText: "スイカカード利用停止中...",
        });

        Fetch("/apis/suica/", "DELETE")
            .then((response: Response) => {
                let msg: string = "";

                switch (response.status) {
                    case 4:
                        msg = "スイカカード利用停止しました";
                        break;
                    case 11:
                        msg =
                            "サインアウトされました、もう一度サインインしてください";
                        break;
                    case 50:
                    default:
                        msg = "スイカカード利用停止できませんでした";
                        break;
                }

                this.setState({
                    stateText: msg,
                });
            })
            .finally(() => {
                this.setState({
                    deregWait: false,
                });
            });
    };

    render() {
        return (
            <div className="row">
                <div className="ui fluid card">
                    <div className="content">
                        <div className="centered header">スイカカード</div>
                        <div className="meta">
                            スイカカードの登録や利用停止の設定
                        </div>
                        {this.state.stateText && (
                            <div className="description">
                                {this.state.stateText}
                            </div>
                        )}
                    </div>
                    <div className="extra content">
                        <div className="ui two buttons">
                            <SuicaButton
                                colour="green"
                                text="スイカ登録"
                                wait={this.state.regWait}
                                onClick={this.registerSuica}
                            />
                            <SuicaButton
                                colour="red"
                                text="利用停止"
                                wait={this.state.deregWait}
                                onClick={this.deregisterSuica}
                            />
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

function SuicaButton(props: {
    colour: string;
    text: string;
    wait: boolean;
    onClick(): void;
}) {
    let load = props.wait ? "loading " : "";
    let list = `ui ${props.colour} ${load}basic button`;

    return (
        <button className={list} onClick={props.onClick} disabled={props.wait}>
            {props.text}
        </button>
    );
}
