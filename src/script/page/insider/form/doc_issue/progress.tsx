import React from "react";
import { RouteComponentProps } from "react-router-dom";

interface RouteParams {
    step: string;
}
interface Props extends RouteComponentProps<RouteParams> {}

export default class Progress extends React.Component<Props> {
    stepState = (step: number) => {
        let current = Number.parseInt(this.props.match.params.step);
        return (
            (current > step ? "completed " : current == step ? "active " : "") +
            "step"
        );
    };

    render() {
        return (
            <div id="progress" className="ui four ordered steps">
                <div className={this.stepState(1)}>
                    <div className="content">
                        <div className="title">基本情報</div>
                        <div className="description">
                            基本的な個人情報と
                            <br />
                            利用規約を同意
                        </div>
                    </div>
                </div>
                <div className={this.stepState(2)}>
                    <div className="content">
                        <div className="title">申し込み書を記入</div>
                    </div>
                </div>
                <div className={this.stepState(3)}>
                    <div className="content">
                        <div className="title">入力情報を確認</div>
                    </div>
                </div>
                <div className={this.stepState(4)}>
                    <div className="content">
                        <div className="title">支払い</div>
                        <div className="description">手続き料を支払う</div>
                    </div>
                </div>
            </div>
        );
    }
}
