import React from "react";

import { default as Support } from "./support";

export interface BaseProp {
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
interface Props extends BaseProp {
    OnSettled(): void;
    OnMount(): void;
}

export default class Payment extends React.Component<Props> {
    componentDidMount = () => this.props.OnMount();

    render() {
        if (window.PaymentRequest) {
            return (
                <Support
                    items={this.props.items}
                    tax={this.props.tax}
                    appID={this.props.appID}
                />
            );
        } else {
            return <NotSupport />;
        }
    }
}

function NotSupport() {
    return (
        <div className="middle aligned">
            <h1 className="ui icon header">
                <i className="warning icon"></i>
                <div className="content">
                    申し込みは完成しました
                    <p className="sub header">
                        申し訳ございません。このブラウザはPayment
                        APIをサポートしないため、
                        <br />
                        お手数ですがお支払いは学生カウンターまでお願い申し上げます。
                    </p>
                </div>
            </h1>
        </div>
    );
}
