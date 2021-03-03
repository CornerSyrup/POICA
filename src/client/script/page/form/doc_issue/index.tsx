import React from "react";
import { RouteComponentProps } from "react-router-dom";

import {
    CommonFields as Common,
    DocIssue as Main,
    DocIssue_Grad as Grad,
    DocIssue_Intl as Intl,
} from "../../../model/form";
import {
    PrefillUserResponse,
    TeacherListResponse,
} from "../../../model/response";
import { Teacher } from "../../../model/teacher";
import { Department } from "../../../model/department";
import { buildCurrencyAmount } from "../../../model/payment";
import { generateApplicationID } from "../../../model/apply";
import { Fetch } from "../../../model/server";

import { default as StepOne } from "../common_form";
import { default as StepTwo } from "./main";
import { default as StepTwoG } from "./grad";
import { default as StepTwoI } from "./intl";
import { default as StepThree } from "../../payment";
import Progress from "./progress";

interface Props extends RouteComponentProps {}
interface State {
    /**
     * Teacher list.
     */
    teachers: Array<Teacher>;
    /**
     * Department list.
     */
    departments: Array<Department>;
    /**
     * Country list, key of code; value of name.
     */
    countries: object;
    /**
     * Step ID which now filling.
     */
    step: string;
}

export default class DocIssue extends React.Component<Props, State> {
    path = this.props.match.path;
    /**
     * Form data.
     */
    data: Main = {
        bc: {
            fn: "",
            fk: "",
            ln: "",
            lk: "",
            si: "",
            cc: "",
            ct: "def",
        },
        db: 0,
        st: 1,
        pp: 0,
        dc: [],
        lg: [],
    };
    fee: PaymentItem = {
        amount: buildCurrencyAmount(200),
        label: "発行料",
    };
    appID: string = "";
    /**
     *
     */
    prefill_common?: Common;

    constructor(props: Props) {
        super(props);

        this.state = {
            teachers: [],
            departments: [],
            countries: [],
            step: "0",
        };

        Fetch("/apis/prefill/users/", "GET")
            .then((response: PrefillUserResponse) => {
                if (response.status == 1) {
                    this.prefill_common = {
                        si: response.data.sid,
                        fn: response.data.fname,
                        fk: response.data.fkana,
                        ln: response.data.lname,
                        lk: response.data.lkana,
                        cc: "",
                        ct: "",
                    };
                }
            })
            .finally(() => {
                this.setState({ step: "1" });
            });

        this.setTeachers();

        generateApplicationID().then((id) => (this.appID = id));
    }

    componentDidMount = () => {
        document.title = "証明書等発行願 - 申し込み";
    };

    //#region data fetcher
    setTeachers = async () => {
        Fetch("/apis/teachers/", "GET").then((response: TeacherListResponse) => {
            if (response.status == 1) {
                this.setState({
                    teachers: response.teachers,
                });
            }
        });
    };

    setDepartments = async () => {
        Fetch("/apis/departments/", "POST").then((respond: Array<Department>) => {
            this.setState({
                departments: respond,
            });
        });
    };

    setCountries = async () => {
        fetch(
            "https://cdn.jsdelivr.net/gh/umpirsky/country-list/data/ja/country.json"
        )
            .then((r) => r.json())
            .then((respond: object) => {
                this.setState({
                    countries: respond,
                });
            });
    };
    //#endregion

    //#region Submit handler
    submitStepOne = (data: Common) => {
        this.data.bc = data;

        this.setState({
            step: "2",
        });
    };

    submitStepTwo = (data: Main) => {
        let step = "3";

        if (data.st == 2) {
            step = "2g";
            this.setDepartments();
        } else if (data.dc[6]) {
            step = "2i";
            this.setCountries();
        }

        this.setState({
            step: step,
        });

        (async () => {
            data.bc = this.data.bc;
            data.db = new Date(data.db).getTime() / 1000;

            let lg = data.lg[0];
            data.lg = data.dc.map(() => Boolean(lg));
            this.data = data;
        })();
    };

    submitStepTwoG = (data: Grad) => {
        let step = "3";
        this.data.gs = data;

        if (this.data.dc[6]) {
            step = "2i";
            this.setCountries();
        }

        this.setState({
            step: step,
        });
    };

    submitStepTwoI = (data: Intl) => {
        this.data.is = data;

        this.setState({
            step: "3",
        });
    };

    sendFormData = () => {
        Fetch("/apis/forms/", "POST", { frm: this.data, typ: "doc" }).then(
            (r: any) => {
                if (r.status != 2) {
                    console.warn(r);
                }
            }
        );
    };

    paymentSettled = () => {
        // post to server,
        // tell server some application is paid.
    };
    //#endregion

    render() {
        return (
            <React.Fragment>
                <Progress step={this.state.step} />

                <div className="ten wide centered column">
                    {this.state.step == "1" && (
                        <StepOne
                            submit={this.submitStepOne}
                            teachers={this.state.teachers}
                            data={this.prefill_common}
                        />
                    )}
                    {this.state.step == "2" && (
                        <StepTwo submit={this.submitStepTwo} />
                    )}
                    {this.state.step == "2g" && (
                        <StepTwoG
                            submit={this.submitStepTwoG}
                            depts={this.state.departments}
                        />
                    )}
                    {this.state.step == "2i" && (
                        <StepTwoI
                            submit={this.submitStepTwoI}
                            countries={this.state.countries}
                        />
                    )}
                    {this.state.step == "3" && (
                        <StepThree
                            items={[this.fee]}
                            appID={this.appID}
                            OnSettled={this.paymentSettled}
                            OnMount={this.sendFormData}
                        />
                    )}
                </div>
            </React.Fragment>
        );
    }
}
