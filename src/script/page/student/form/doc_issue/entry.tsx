import React from "react";
import { RouteComponentProps } from "react-router-dom";

import {
    CommonFields as Common,
    DocIssue as Main,
    DocIssue_Grad as Grad,
    DocIssue_Intl as Intl,
} from "../../../../model/form_fields";
import { Teacher } from "../../../../model/teacher";
import { Department } from "../../../../model/department";
import { buildCurrencyAmount } from "../../../../model/payment";
import { generateApplicationID } from "../../../../model/apply";

import { default as StepOne } from "../common_form";
import { default as StepTwo } from "./main";
import { default as StepTwoG } from "./grad";
import { default as StepTwoI } from "./intl";
import { default as StepThree } from "../payment/main";
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
            ct: "",
        },
        db: 0,
        st: 0,
        pp: 0,
        dc: [],
        lg: [],
    };
    fee: PaymentItem = {
        amount: buildCurrencyAmount(200),
        label: "発行料",
    };
    appID: string = "";

    constructor(props: Props) {
        super(props);

        this.state = {
            teachers: [],
            departments: [],
            countries: [],
            step: "1",
        };

        this.setTeachers();

        generateApplicationID().then((id) => (this.appID = id));
    }

    componentDidMount = () => {
        document.title = "証明書等発行願 - 申し込み";
    };

    //#region data fetcher
    setTeachers = async () => {
        fetch("/teachers/", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            // body: JSON.stringify(data),
        })
            .then((r) => r.json())
            .then((respond: Array<Teacher>) => {
                this.setState({
                    teachers: respond,
                });
            });
    };

    setDepartments = async () => {
        fetch("/departments/", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            // body: JSON.stringify(data),
        })
            .then((r) => r.json())
            .then((respond: Array<Department>) => {
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

        async () => {
            data.bc = this.data.bc;
            data.db = new Date(data.db).getTime() / 1000;

            let lg = data.lg[0];
            data.lg = data.dc.map(() => lg);
            this.data = data;
        };
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
        fetch("/forms/", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ frm: this.data, typ: "doc" }),
        })
            .then((r) => r.json())
            .then((r: any) => {
                if (r.status != 2) {
                    console.warn(r);
                }
            });
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
