import React from "react";
import { Switch, Route, Redirect, RouteComponentProps } from "react-router-dom";

import { CommonFields as Common } from "../../../model/FormFields";
import { Teacher } from "../../../model/Teacher";

import { default as StepOne } from "./CommonFieldForm";

interface Props extends RouteComponentProps {}
interface State {
    /**
     * Teacher list.
     */
    teachers: Array<Teacher>;
    /**
     * Common fields.
     */
    common: Common;
}

export default class DocIssue extends React.Component<Props, State> {
    constructor(props: Props) {
        super(props);

        this.state = {
            teachers: [],
            common: {
                fn: "",
                fk: "",
                ln: "",
                lk: "",
                si: 0,
                cc: "",
                ct: 0,
            },
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
        this.props.history.replace(`${this.props.match.path}/2`);
        this.setState({
            common: data,
        });
    };

    render() {
        let path = this.props.match.path;

        return (
            <React.Fragment>
                <Switch>
                    <Route
                        exact
                        path={`${path}/:step`}
                        children={<h1>Progress Bar</h1>}
                    />
                </Switch>

                <Switch>
                    <Route
                        exact
                        path={`${path}/1`}
                        children={
                            <StepOne
                                submit={this.submitStepOne}
                                teachers={this.state.teachers}
                            />
                        }
                    />
                    <Redirect to={`${path}/1`} />
                </Switch>
            </React.Fragment>
        );
    }
}
