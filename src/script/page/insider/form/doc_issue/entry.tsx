import React from "react";
import { Switch, Route, Redirect, RouteComponentProps } from "react-router-dom";

import {
    CommonFields as Common,
    DocIssue as Main,
    DocIssue_Grad as Grad,
} from "../../../../model/form_fields";
import { Teacher } from "../../../../model/teacher";
import { Department } from "../../../../model/department";

import { default as StepOne } from "../common_form";
import { default as StepTwo } from "./main";
import { default as StepTwoG } from "./grad";
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

    constructor(props: Props) {
        super(props);

        this.state = {
            teachers: [],
            departments: [],
        };

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
    }

    componentDidMount = () => {
        document.title = "証明書等発行願 - 申し込み";
    };

    submitStepOne = (data: Common) => {
        this.data.bc = data;
        this.props.history.replace(`${this.path}/2`);
    };

    submitStepTwo = (data: Main) => {
        let dest = "3";
        if (data.st == 2) {
            dest = "2g";

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
        } else if (data.dc[6]) {
            dest = "2i";
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
        let dest = this.data.dc[6] ? "2i" : "3";
        this.props.history.replace(`${this.path}/${dest}`);
    };

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
                    <Redirect to={`${this.path}/1`} />
                </Switch>
            </React.Fragment>
        );
    }
}
