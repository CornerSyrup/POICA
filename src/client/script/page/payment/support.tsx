import React from "react";
import { Link, RouteComponentProps, withRouter } from "react-router-dom";

import "../../model/payment";
import { buildCurrencyAmount } from "../../model/payment";
import { BaseProp as Base } from "./index";

interface Props extends Base, RouteComponentProps {}
interface State {
    /**
     * Payment status.
     * 0 as default; 1 as processing; 2 as success.
     * -1 as error encountered.
     */
    status: number;
}

class Support extends React.Component<Props, State> {
    /**
     * Supported payment methods.
     */
    method: PaymentMethodData = {
        supportedMethods: "basic-card",
    };
    /**
     *  Detail of the payment.
     */
    detail: PaymentDetailsInit = {
        displayItems: this.props.items,
        total: {
            amount: buildCurrencyAmount(
                this.props.items
                    .map((item) => Number.parseInt(item.amount.value))
                    .reduce((prev, next) => (prev += next))
            ),
            label: "合計",
        },
    };

    constructor(props: Props) {
        super(props);
        this.state = {
            status: 0,
        };
    }

    pay = () => {
        let req = new PaymentRequest([this.method], this.detail);

        this.setState({
            status: 1,
        });

        req.show()
            .then((respond: PaymentResponse) => {
                setTimeout(() => {
                    this.setState({
                        status: 2,
                    });
                    respond.complete();
                }, 2500);
            })
            .catch((reason) => {
                console.table(reason);
                this.setState({
                    status: -1,
                });
            });
    };

    leave = () => {
        this.props.history.push("/form");
    };

    render() {
        let Message: JSX.Element = <React.Fragment />;

        if (this.state.status == 1) {
            Message = <ProcessingMessage />;
        } else if (this.state.status == 2) {
            Message = <SuccessMessage />;
        } else if (this.state.status < 0) {
            Message = (
                <FailMessage reason="お支払い処理はキャンセルされました" />
            );
        }

        return (
            <div>
                {Message}
                <div className="ui fluid card">
                    <div className="content">
                        <div className="centered header">
                            <h1>支払い</h1>
                        </div>
                        {this.props.appID && (
                            <div className="meta">
                                この申し込みの処理番号は #
                                <span>{this.props.appID}</span>です。
                            </div>
                        )}

                        <div className="description">
                            <p>ここの手続きは仮です。</p>
                            <p>
                                お使いになったカードデータは検証されないし、
                                サーバーには伝送しません。
                            </p>
                        </div>
                    </div>
                    <div className="extra content">
                        <div className="ui three buttons">
                            <button
                                className="ui green animated basic button"
                                onClick={
                                    this.state.status != 2
                                        ? this.pay
                                        : this.leave
                                }
                            >
                                <span className="visible content">
                                    {this.state.status != 2
                                        ? "オンライン"
                                        : "支払いました"}
                                </span>
                                <span className="hidden content">
                                    {this.state.status != 2
                                        ? "今すぐ支払う"
                                        : "フォームページに戻る"}
                                </span>
                            </button>
                            <Link
                                to="/form"
                                className="ui grey animated basic button"
                            >
                                <span className="visible content">
                                    あとで支払う
                                </span>
                                <span className="hidden content">
                                    フォームページに戻る
                                </span>
                            </Link>
                            <Link
                                to="/form"
                                className="ui red animated basic button"
                            >
                                <span className="visible content">
                                    学生カウンターで支払う
                                </span>
                                <span className="hidden content">
                                    フォームページに戻る
                                </span>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

function ProcessingMessage() {
    return (
        <div className="ui icon message">
            <i className="notched circle loading icon"></i>
            <div className="content">
                <div className="header">お支払いは処理中です</div>
                <p>完了まで少々お待ちください。</p>
            </div>
        </div>
    );
}

function SuccessMessage() {
    return (
        <div className="ui positive icon message">
            <i className="yen sign icon"></i>
            <div className="content">
                <div className="header">お支払い成功しました</div>
                <p>ありがとうございました。</p>
            </div>
        </div>
    );
}

function FailMessage(props: { reason: string }) {
    return (
        <div className="ui negative icon message">
            <i className="yen sign icon"></i>
            <div className="content">
                <div className="header">お支払いはまた完成していません</div>
                <p>{props.reason}</p>
            </div>
        </div>
    );
}

export default withRouter(Support);
