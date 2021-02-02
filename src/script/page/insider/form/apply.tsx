import React from "react";
import { Switch, Route, Redirect, RouteComponentProps } from "react-router-dom";

import { default as Form } from "./DocIssue";

interface Props extends RouteComponentProps {}
interface State {}

export default class Apply extends React.Component<Props, State> {
    render() {
        let path = this.props.match.path;
        return (
            <Switch>
                <Route path={`${path}/docissue`} component={Form} />
                <Redirect to={`${path}/docissue`} />
            </Switch>
        );
    }
}
