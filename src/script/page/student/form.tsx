import React from "react";
import { Switch, Route, RouteComponentProps, Redirect } from "react-router-dom";

import Apply from "./form/apply";
import Main from "./form/main";

interface Props extends RouteComponentProps {}
interface State {}

export default class Form extends React.Component<Props, State> {
    componentDidMount = () => {
        document.title = "フォーム";
    };

    render() {
        let path = this.props.match.path;

        return (
            <Switch>
                <Route strict path={`${path}/apply`} component={Apply} />
                <Route exact path={`${path}/`} component={Main} />
                <Redirect to={`${path}/`} />
            </Switch>
        );
    }
}
