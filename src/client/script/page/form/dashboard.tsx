import React from "react";
import { Link, RouteComponentProps } from "react-router-dom";

import {
    typeIdTranslate as idTranslate,
    status2Percent as toPercent,
    status2String as toStatus,
} from "../../model/apply";
import { FormCatalogueResponse as Response } from "../../model/response";
import { FormCatalogueItem as Item } from "../../model/form";
import { CreateURL, Fetch } from "../../model/server";

interface Props extends RouteComponentProps {}
interface State {
    list: Array<Item>;
    stat: {
        applied: number;
        process: number;
        done: number;
    };
    fetchRes: number;
    fetchWait: boolean;
}

export default class Main extends React.Component<Props, State> {
    componentDidUpdate = () => {
        $(".ui.progress").progress();
    };

    constructor(props: Props) {
        super(props);

        this.state = {
            list: [],
            stat: {
                applied: 0,
                done: 0,
                process: 0,
            },
            fetchRes: -1,
            fetchWait: false,
        };

        this.fetchAppliedForms();
    }

    fetchAppliedForms = async () => {
        this.setState({
            fetchWait: true,
        });

        Fetch("apis/forms/", "GET")
            .then((res: Response) => {
                this.setState({
                    fetchRes: res.status,
                });

                if (res.status == 1) {
                    let l = res.cat?.map((i) => {
                        i.status = Number.parseInt(
                            (i.status as unknown) as string,
                            2
                        );
                        return i;
                    });

                    this.setState(
                        {
                            list: l,
                        },
                        this.statAppliedForms
                    );
                }
            })
            .finally(() => {
                this.setState({
                    fetchWait: false,
                });
            });
    };

    statAppliedForms = async () => {
        //p for precessing; d for done
        let p: number = 0;
        let d: number = 0;

        this.state.list.forEach((f: Item) => {
            if (f.status == 3) {
                d++;
            } else if (f.status == 0) {
            } else {
                p++;
            }
        });

        this.setState({
            stat: {
                applied: this.state.list.length,
                done: d,
                process: p,
            },
        });
    };

    render() {
        return (
            <div className="ui grid">
                {this.state.fetchRes == 1 || this.state.fetchRes == -1 || (
                    <div className="eight wide column centered row">
                        <Message status={this.state.fetchRes} />
                    </div>
                )}
                <header className="centered row" style={{ flexGrow: 0 }}>
                    <div className="ten wide column">
                        <div className="ui three cards">
                            <StatCard
                                colour="teal"
                                header="申請済み"
                                value={this.state.stat.applied}
                            />
                            <StatCard
                                colour="yellow"
                                header="処理中"
                                value={this.state.stat.process}
                            />
                            <StatCard
                                colour="green"
                                header="完成"
                                value={this.state.stat.done}
                            />
                        </div>
                    </div>
                </header>
                <section className="row">
                    <div className="twelve wide column">
                        <table className="ui selectable table">
                            <thead>
                                <tr>
                                    <th className="three wide">書類</th>
                                    <th className="three wide">申請日</th>
                                    <th className="seven wide">進捗状況</th>
                                    <th className="three wide"></th>
                                </tr>
                            </thead>
                            <tbody>
                                {this.state.list?.map((f: Item) => (
                                    <ApplyItem data={f} />
                                ))}
                            </tbody>
                        </table>
                    </div>
                    <div className="four wide column">
                        <div className="ui fluid card">
                            <div className="content">
                                <div className="header">申し込みを作成</div>
                                <div className="meta">証明書発行願</div>
                            </div>
                            <Link
                                to={`/form/apply/`}
                                className="ui bottom attached green button"
                            >
                                申し込む
                            </Link>
                        </div>
                        <div className="ui fluid card">
                            <div className="content">
                                <div className="header">読み込み</div>
                                <div className="meta">申し込みリストを更新</div>
                            </div>
                            <button
                                className={`ui bottom attached green ${
                                    this.state.fetchWait ? "loading " : ""
                                } button`}
                                onClick={this.fetchAppliedForms}
                                disabled={this.state.fetchWait}
                            >
                                更新
                            </button>
                        </div>
                    </div>
                </section>
            </div>
        );
    }
}

function Message(props: { status: number }) {
    let header: string = "";
    let content: JSX.Element = <React.Fragment />;

    switch (props.status) {
        case 11:
            header = "すでにサインアウトされました";
            content = (
                <p>
                    ここから<a href={CreateURL("signin/")}>サインイン</a>
                    してください
                    <br />
                    サインアウトした覚えがなければ、すみやかにパスワードを変更してください
                </p>
            );
            break;
        case 20:
            header = "読み込みが失敗しました";
            content = <p>しばらく待ってからもう一度試してみてください</p>;
    }

    return (
        <div className="ui info icon message">
            <i className="attention icon"></i>
            <div className="content">
                <div className="header">{header}</div>
                {content}
            </div>
        </div>
    );
}

function StatCard(props: {
    colour: string;
    header: string;
    meta?: string;
    value: number | string;
    label?: string;
}) {
    return (
        <div className={`${props.colour} card segment`}>
            <div className="content">
                <div className="header">{props.header}</div>
                <div className="meta">{props.meta}</div>
                <div
                    className={`ui right floated ${props.colour} horizontal statistic`}
                >
                    <div className="value">{props.value}</div>
                    <div className="label">{props.label}</div>
                </div>
            </div>
        </div>
    );
}

function ApplyItem(props: { data: Item }) {
    props.data.status = props.data.status as number;

    $(".ui.progress").progress();
    return (
        <tr>
            <td>
                <Link to={`/form/${props.data.id}`}>
                    {idTranslate(props.data.type)}
                </Link>
            </td>
            <td>{props.data.date}</td>
            <td className="middle aligned">
                <div
                    className={`ui ${
                        props.data.status == 0 ? "teal" : "yellow"
                    } progress`}
                    data-percent={toPercent(props.data.status)}
                >
                    <div className="bar"></div>
                    <div className="label"></div>
                </div>
            </td>
            <td>{toStatus(props.data.status)}</td>
        </tr>
    );
}
