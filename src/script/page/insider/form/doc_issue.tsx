import React from "react";
import { Switch, Route, Redirect, RouteComponentProps } from "react-router-dom";

import {
    CommonFields as Common,
    DocIssue as Main,
} from "../../../model/form_fields";
import { Teacher } from "../../../model/teacher";

import { default as StepOne } from "./common_form";
import { default as StepTwo } from "./doc_issue/main";
import Progress from "./doc_issue/progress";

interface Props extends RouteComponentProps {}
interface State {
    /**
     * Teacher list.
     */
    teachers: Array<Teacher>;
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
        };

        fetch("/teacher/", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            // body: JSON.stringify(data),
        })
            .then((r) => r.json())
            .then((respond: any) => {
                this.setState({
                    teachers: respond.teachers,
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
        let dest = data.dc[3] || data.dc[4] ? "2g" : data.dc[6] ? "2i" : "3";
        data.bc = this.data.bc;
        this.data = data;
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
                    {this.data.bc.cc || <Redirect to={`${this.path}/1`} />}
                    <Route
                        exact
                        path={`${this.path}/2`}
                        children={<StepTwo submit={this.submitStepTwo} />}
                    />
                    <Redirect to={`${this.path}/1`} />
                </Switch>
            </React.Fragment>
        );
    }
}
