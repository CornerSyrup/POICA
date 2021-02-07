import React from "react";

import "../../../../model/payment";
import { buildCurrencyAmount } from "../../../../model/payment";

export interface Props {
    /**
     * Items to be paid.
     */
    items: PaymentItem[];
    /**
     * Whether tax is required, tax will be 10%.
     */
    tax?: boolean;
    /**
     * Application ID.
     */
    appID?: string;
}
interface State {
    /**
     * 0 as default;
     * 1 as processing;
     * 2 as success.
     * -1 as error encountered.
     */
    status: number;
}

export default class Support extends React.Component<Props, State> {
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

    render() {
        return (
            <div>
                {this.state.status == 1 && <ProcessingMessage />}
                {this.state.status == 2 && <SuccessMessage />}
                {this.state.status < 0 && (
                    <FailMessage reason="お支払い処理はキャンセルされました" />
                )}
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
                                className="ui green basic button"
                                data-tooltip={
                                    this.state.status != 2
                                        ? "今すぐPayment APIで支払う"
                                        : "すでに支払いました"
                                }
                                data-position="bottom left"
                                onClick={
                                    this.state.status != 2
                                        ? this.pay
                                        : undefined
                                }
                            >
                                オンライン
                            </button>
                            <button
                                className="ui grey basic button"
                                data-tooltip="後ほどフォームの支払いページで支払う"
                                data-position="bottom center"
                            >
                                あとで支払う
                            </button>
                            <button
                                className="ui red basic button"
                                data-tooltip="学生カウンターで申し込み番号を提示して支払う"
                                data-position="bottom right"
                            >
                                学生カウンター
                            </button>
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
