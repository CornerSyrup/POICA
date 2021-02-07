import React from "react";
import { Switch, Route, Redirect, RouteComponentProps } from "react-router-dom";

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
            si: 0,
            cc: "",
            ct: 0,
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
        this.props.history.replace(`${this.path}/2`);
    };

    submitStepTwo = (data: Main) => {
        let dest = "3";
        if (data.st == 2) {
            dest = "2g";
            this.setDepartments();
        } else if (data.dc[6]) {
            dest = "2i";
            this.setCountries();
        }
        this.props.history.replace(`${this.path}/${dest}`);

        async () => {
            data.bc = this.data.bc;
            data.db = new Date(data.db).getTime() / 1000;

            let lg = data.lg[0];
            data.lg = data.dc.map(() => lg);
            this.data = data;
        };
    };

    submitStepTwoG = (data: Grad) => {
        this.data.gs = data;

        let dest;
        if (this.data.dc[6]) {
            dest = "2i";
            this.setCountries();
        } else {
            dest = "3";
        }
        this.props.history.replace(`${this.path}/${dest}`);
    };

    submitStepTwoI = (data: Intl) => {
        this.data.is = data;
        this.props.history.replace(`${this.path}/3`);
    };

    paymentSettled = () => {
        // post to server,
        // tell server some application is paid.
    };
    //#endregion

    render() {
        return (
            <React.Fragment>
                <Switch>
                    <Route
                        exact
                        path={`${this.path}/:step`}
                        component={Progress}
                    />
                </Switch>

                <div className="ten wide column centered">
                    <Switch>
                        {/* Prevent direct access to fall through. */}
                        {/* Fields used to check are just because required */}
                        <Route
                            exact
                            path={`${this.path}/1`}
                            children={
                                <StepOne
                                    submit={this.submitStepOne}
                                    teachers={this.state.teachers}
                                />
                            }
                        />
                        {/* {this.data.bc.cc || <Redirect to={`${this.path}/1`} />} */}
                        <Route
                            exact
                            path={`${this.path}/2`}
                            children={<StepTwo submit={this.submitStepTwo} />}
                        />
                        {/* {this.data.pp || <Redirect to={`${this.path}/2`} />} */}
                        <Route
                            exact
                            path={`${this.path}/2g`}
                            children={
                                <StepTwoG
                                    submit={this.submitStepTwoG}
                                    depts={this.state.departments}
                                />
                            }
                        />
                        {/* {this.data.st == 2 || <Redirect to={`${this.path}/2g`} />} */}
                        <Route
                            exact
                            path={`${this.path}/2i`}
                            children={
                                <StepTwoI
                                    submit={this.submitStepTwoI}
                                    countries={this.state.countries}
                                />
                            }
                        />
                        {/* {this.data.dc[6] || <Redirect to={`${this.path}/2i`} />} */}
                        <Route
                            exact
                            path={`${this.path}/3`}
                            children={
                                <StepThree
                                    items={[this.fee]}
                                    appID={this.appID}
                                    OnSettled={this.paymentSettled}
                                />
                            }
                        />
                        {/* {this.data.dc[6] || <Redirect to={`${this.path}/3`} />} */}
                        <Redirect to={`${this.path}/1`} />
                    </Switch>
                </div>
            </React.Fragment>
        );
    }
}
