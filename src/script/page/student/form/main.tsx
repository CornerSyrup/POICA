import React from "react";
import { Link, RouteComponentProps } from "react-router-dom";

import {
    typeIdTranslate as idTranslate,
    status2Percent as toPercent,
    status2String as toStatus,
} from "../../../model/apply";
import {
    FormCatalogueResponse as Response,
    FormCatalogueItem as Item,
} from "../../../model/respond";

interface Props extends RouteComponentProps {}
interface State {
    list: Array<Item>;
    stat: {
        applied: number;
        process: number;
        done: number;
    };
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
        };
        this.fetchAppliedForms();
    }

    fetchAppliedForms = async () => {
        fetch("/forms/", {
            method: "GET",
            headers: { "Content-Type": "application/json" },
        })
            .then((r) => r.json())
            .then((res: Response) => {
                let l = res.cat.map((i) => {
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
                                    <ApplyItem
                                        key={f.id}
                                        type={f.type}
                                        date={f.date.toString()}
                                        status={(f.status as unknown) as number}
                                        id={f.id}
                                    />
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
                                className="ui bottom attached green button"
                                onClick={this.fetchAppliedForms}
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

function ApplyItem(props: {
    type: string;
    date: string;
    status: number;
    id: number;
}) {
    $(".ui.progress").progress();
    return (
        <tr>
            <td>
                <Link to={`/form/${props.id}`}>{idTranslate(props.type)}</Link>
            </td>
            <td>{props.date}</td>
            <td className="middle aligned">
                <div
                    className={`ui ${
                        props.status == 0 ? "teal" : "yellow"
                    } progress`}
                    data-percent={toPercent(props.status)}
                    style={{ margin: 0 }}
                >
                    <div className="bar"></div>
                    <div className="label"></div>
                </div>
            </td>
            <td>{toStatus(props.status)}</td>
        </tr>
    );
}
