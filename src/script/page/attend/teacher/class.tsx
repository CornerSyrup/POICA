import React from "react";
import { RouteComponentProps } from "react-router-dom";

import ReadIDm, { pair } from "../../../model/felica";
import { getSHA256 as getHash } from "../../../model/hash";
import { StudentListRespond as Response } from "../../../model/response";
import { Fetch } from "../../../model/server";
import { BriefStudent as Student } from "../../../model/student";

interface AttendPage {
    class: string;
}
interface AttendRecord {
    sid: string;
    time: number;
}

interface Props extends RouteComponentProps<AttendPage> {}
interface State {
    //#region decision data
    students: Array<Student>;
    /**
     * Map student id as key to whether attended as value.
     */
    attends: Map<string, boolean>;
    /**
     * Map suica idm as key to student id as value.
     */
    suicaIDm: Map<string, string>;
    //#endregion

    //#region state controller
    /**
     * Activating suica reader.
     */
    suica_load: boolean;
    /**
     * Fetching student list.
     */
    student_load: boolean;
    /**
     * Is reading with suica reader.
     */
    suica_reading: boolean;
    /**
     * Re-stat loading.
     */
    stat_load: boolean;
    //#endregion

    stat: {
        person: number;
        rate: number;
        attend: number;
    };
}

export default class AttendClass extends React.Component<Props, State> {
    /**
     * Suica read timeout interval.
     */
    suicaTimeout: any = undefined;
    /**
     * Suica reader web usb device object.
     */
    suicaReader: any = undefined;

    /**
     * Timeout of push to server.
     */
    pushTimeout: any = undefined;

    //#region data to push
    /**
     * Array of new attend students, which not yet push to server.
     */
    present: Array<AttendRecord> = [];
    /**
     * Array of student to be leave, which not yet push to server.
     */
    leave: Array<AttendRecord> = [];
    //#endregion

    componentDidMount = () => {
        document.title = `${this.props.match.params.class.toUpperCase()} - 出席管理`;
    };

    componentWillUnmount = () => {
        clearTimeout(this.suicaTimeout);
    };

    constructor(props: Props) {
        super(props);

        this.state = {
            students: [],
            attends: new Map<string, boolean>(),
            suicaIDm: new Map<string, string>(),
            suica_load: false,
            suica_reading: false,
            student_load: true,
            stat_load: false,
            stat: {
                person: 0,
                rate: 0,
                attend: 0,
            },
        };

        this.fetchStudents();
    }

    //#region Attendance
    /**
     * Toggle attendance of student.
     * @param sid student id.
     */
    toggleAttend = (sid: string) => {
        this.setAttend(sid, !(this.state.attends.get(sid) || false));
    };

    /**
     * Set a student's attendance
     * @param sid Student ID
     * @param attend Attend for true, vice versa.
     */
    setAttend = (sid: string, attend: boolean) => {
        const find = (r: AttendRecord) => r.sid == sid;
        let att: number = this.state.stat.attend;

        // from not attend to attend
        if (attend && !this.state.attends.get(sid)) {
            att++;
            (async () => {
                let p = this.present.findIndex(find);
                let l = this.leave.findIndex(find);

                // in leave; treat as cancel
                if (l != -1) {
                    this.leave.splice(l, 1);
                }
                // cannot find in attend, then register
                else if (p == -1) {
                    this.present.push({
                        sid: sid,
                        time: new Date().getTime() / 1000,
                    });
                }
            })();
        }
        // from attend to not attend
        else if (!attend && this.state.attends.get(sid)) {
            att--;
            (async () => {
                let p = this.present.findIndex(find);
                let l = this.leave.findIndex(find);

                // in attend, treat as cancel
                if (p != -1) {
                    this.present.splice(p, 1);
                }
                // cannot find in leave, then register
                else if (l == -1) {
                    this.leave.push({
                        sid: sid,
                        time: new Date().getTime() / 1000,
                    });
                }
            })();
        }

        this.setState({
            attends: this.state.attends.set(sid, attend),
            stat: {
                attend: att,
                person: this.state.stat.person,
                rate: Math.round((att / this.state.stat.person) * 100),
            },
        });
    };

    /**
     * Invoke stat of attendance, perhaps manually.
     */
    restatAttend = async () => {
        this.setState({
            stat_load: true,
        });

        let ppl = this.state.students.length;
        let att = 0;
        this.state.students.forEach((std) => {
            if (this.state.attends.get(std.sid)) att++;
        });
        let rat = Math.round((att / ppl) * 100);

        this.setState({
            stat: {
                attend: att,
                person: ppl,
                rate: isNaN(rat) ? 0 : rat,
            },
            stat_load: false,
        });
    };
    //#endregion

    // #region server interaction
    /**
     * Fetch student list.
     */
    fetchStudents = async () => {
        this.setState({
            student_load: true,
        });

        Fetch(`/students/${this.props.match.params.class}/`, "GET").then(
            (response: Response) => {
                if (response.status == 1) {
                    this.filterSuica(response.students);
                    this.setState(
                        {
                            students: response.students,
                        },
                        () =>
                            this.setState(
                                {
                                    student_load: false,
                                },
                                this.restatAttend
                            )
                    );
                }
            }
        );
    };

    /**
     * Push attend data to sever.
     */
    pushStudent = () => {
        Fetch(`/students/${this.props.match.params.class}`, 'POST', {
            code: this.props.match.params.class,
            attend: this.state.stat.attend,
            list: Array.from(this.state.attends.keys()),
            total: this.state.stat.person,
        });
    };
    //#endregion

    //#region Suica
    /**
     * Filter list of students, produce map of idm and sid, and set to state.
     * @param list List of students.
     */
    filterSuica = async (list: Array<Student>) => {
        let idmMap = new Map<string, string>();

        list.filter((std) => std.suica).forEach((std) => {
            idmMap.set(std.suica as string, std.sid);
        });

        this.setState({
            suicaIDm: idmMap,
        });
    };

    initSuicaRead = async () => {
        this.setState({
            suica_load: true,
        });

        this.suicaReader = await pair();

        this.setState({
            suica_load: false,
            suica_reading: true,
        });

        this.suicaTimeout = setInterval(this.suicaRead, 1500);
    };

    suicaRead = async () => {
        let cde = "";

        try {
            cde = await ReadIDm(this.suicaReader);
        } catch (e) {
            if (e.code) {
                this.finalSuicaRead();
            }
        }

        let sid = this.state.suicaIDm.get(await getHash(cde));

        // idm found in students list
        if (sid) {
            this.setAttend(sid, true);
        }
        // idm not found in student list
        else {
            // show message of nor reg or wrong class.
        }
    };

    finalSuicaRead = () => {
        this.setState({
            suica_load: true,
        });

        clearInterval(this.suicaTimeout);

        this.setState({
            suica_load: false,
            suica_reading: false,
        });
    };
    //#endregion

    render() {
        return (
            <div className="ui grid">
                <div className="centered row" style={{ height: "min-content" }}>
                    <div className="eight wide column">
                        <Message
                            wait={this.state.suica_reading}
                            class={this.props.match.params.class}
                        />
                    </div>
                </div>
                <div className="centered row" style={{ height: "min-content" }}>
                    <div className="ten wide column">
                        <div className="ui three cards">
                            <StatCard
                                colour={
                                    this.state.stat.rate > 50
                                        ? "green"
                                        : "yellow"
                                }
                                header="出席者人数"
                                value={this.state.stat.attend}
                                label="人"
                            />
                            <StatCard
                                colour={
                                    this.state.stat.rate > 50
                                        ? "green"
                                        : "yellow"
                                }
                                header="出席率"
                                value={this.state.stat.rate}
                                label="%"
                            />
                            <StatCard
                                colour="green"
                                header="授業人数"
                                value={this.state.stat.person}
                                label="人"
                            />
                        </div>
                    </div>
                </div>
                <div className="row">
                    <div className="ten wide column">
                        <div className="ui middle aligned divided list">
                            {this.state?.students?.map((s: Student) => (
                                <AttendItem
                                    key={s.sid}
                                    attend={this.state.attends.get(s.sid)}
                                    name={`${s.lname} ${s.fname}`}
                                    id={s.sid}
                                    toggle={this.toggleAttend}
                                />
                            ))}
                        </div>
                    </div>
                    <div className="six wide column">
                        {(navigator as any).usb && (
                            <ButtonCard
                                header="スイカリーダー"
                                meta="Sony RC-S380"
                                loading={this.state.suica_load}
                                text={
                                    this.state.suica_reading
                                        ? "読み取り停止"
                                        : "読み取り開始"
                                }
                                onClick={
                                    this.state.suica_reading
                                        ? this.finalSuicaRead
                                        : this.initSuicaRead
                                }
                            />
                        )}
                        <ButtonCard
                            header="学生リスト更新"
                            meta="サーバーに授業の学生名簿を再度読み込み"
                            loading={this.state.student_load}
                            text="読み込む"
                            onClick={this.fetchStudents}
                        />
                        <ButtonCard
                            header="出席再集計"
                            meta="出席に関するデータを再集計"
                            loading={this.state.stat_load}
                            text="再集計"
                            onClick={this.restatAttend}
                        />
                    </div>
                </div>
            </div>
        );
    }
}

function Message(props: { wait: boolean; class: string }) {
    return (
        <div className="ui info icon message">
            {props.wait ? (
                <i className="notched circle loading icon"></i>
            ) : (
                <i className="attention icon"></i>
            )}
            <div className="content">
                <div className="header">
                    {`${props.class.toUpperCase()}の授業の出席管理ページです`}
                </div>
                出席登録を受け付けています
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

function AttendItem(props: {
    name: string;
    attend: boolean | undefined;
    toggle(sid: string): void;
    id: string;
}) {
    let txt = props.attend ? "出席" : "未出席";
    let clr = props.attend ? "green" : "red";
    let hid = props.attend ? "check" : "times";

    return (
        <div className="item">
            <div className="right floated content">
                <button
                    className={`ui animated ${clr} fade button`}
                    tabIndex={0}
                    onClick={() => props.toggle(props.id)}
                    style={{ width: 96 }}
                >
                    <div className="visible content">{txt}</div>
                    <div className="hidden content">
                        <i className={`${hid} icon`}></i>
                    </div>
                </button>
            </div>
            <img className="ui avatar image" src="/cdn/user.png" />
            <div className="content">{props.name}</div>
        </div>
    );
}

function ButtonCard(props: {
    header: string;
    meta?: string;
    loading: boolean;
    text: string;
    onClick?(): void;
}) {
    let lad = props.loading ? "loading " : "";
    return (
        <div className="ui fluid card">
            <div className="content">
                <div className="header">{props.header}</div>
                <div className="meta">{props.meta}</div>
            </div>
            <button
                className={`ui bottom attached green ${lad}button`}
                onClick={props.onClick || undefined}
            >
                {props.text}
            </button>
        </div>
    );
}
