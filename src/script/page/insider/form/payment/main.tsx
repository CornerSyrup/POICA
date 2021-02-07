import React from "react";

import { default as Support, Props } from "./support";

export default class Payment extends React.Component<Props> {
    render() {
        return (
            <React.Fragment>
                {window.PaymentRequest ? (
                    <Support
                        items={this.props.items}
                        tax={this.props.tax}
                        appID={this.props.appID}
                    />
                ) : (
                    <NotSupport />
                )}
            </React.Fragment>
        );
    }
}

function NotSupport() {
    return (
        <div className="middle aligned">
            <h1 className="ui icon header">
                <i className="warning icon"></i>
                <div className="content">
                    Payment APIのサポート
                    <p className="sub header">
                        申し訳ございません。 このブラウザはPayment
                        APIをサポートしないため、
                        <br />
                        お手数ですがお支払いは学生カウンターまでお願い申し上げます。
                    </p>
                </div>
            </h1>
        </div>
    );
}
