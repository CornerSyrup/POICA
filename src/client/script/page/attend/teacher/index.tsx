import React from "react";
import { Link, RouteComponentProps } from "react-router-dom";

import { Fetch } from "../../../model/server";
import { LessonItem as Item } from "../../../model/lesson";
import { LessonListResponse as Response } from "../../../model/response";

interface Props extends RouteComponentProps {}
interface State {
    list: Array<Item>;
}

export default class Attend extends React.Component<Props, State> {
    path = this.props.match.path;

    componentDidMount = () => {
        document.title = "出席管理";
    };

    componentDidUpdate = () => {
        $(".ui.progress").progress();
    };

    constructor(props: Props) {
        super(props);

        this.state = {
            list: [],
        };

        this.fetchClassAttend();
    }

    fetchClassAttend = async () => {
        Fetch("apis/lessons/", "GET").then((response: Response) => {
            this.setState({
                list: response.lessons,
            });
        });
    };

    render() {
        return (
            <React.Fragment>
                <div
                    className="right floated two wide column"
                    style={{ maxHeight: 66 }}
                >
                    <button
                        className="ui primary button"
                        onClick={this.fetchClassAttend}
                    >
                        更新
                    </button>
                </div>
                <div className="row">
                    <table className="ui selectable table">
                        <thead>
                            <tr>
                                <th className="two wide center aligned">
                                    科目記号
                                </th>
                                <th className="twelve wide">出席率</th>
                                <th className="two wide center aligned"></th>
                            </tr>
                        </thead>
                        <tbody>
                            {this.state?.list?.map((i: Item) => (
                                <LessonItem
                                    key={i.code}
                                    path={`${this.path}/${i.code}`}
                                    code={i.code}
                                    rate={Math.round(
                                        (i.attend / i.total) * 100
                                    )}
                                />
                            ))}
                        </tbody>
                    </table>
                </div>
            </React.Fragment>
        );
    }
}

//#region Item
function LessonItem(props: { path: string; code: string; rate: number }) {
    let colour = "";

    if (props.rate > 75) {
        colour = "teal";
    } else {
        colour = props.rate > 30 ? "yellow" : "red";
    }

    return (
        <tr>
            <td className="center aligned">
                <Link to={props.path}>{props.code.toUpperCase()}</Link>
            </td>
            <td className="middle aligned">
                <div
                    className={`ui ${colour} progress`}
                    data-percent={props.rate}
                >
                    <div className="bar"></div>
                    <div className="label"></div>
                </div>
            </td>
            <td className="center aligned">{props.rate}%</td>
        </tr>
    );
}
//#endregion
