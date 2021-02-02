import React from "react";
import { Switch, Route, Link, RouteComponentProps } from "react-router-dom";

import Apply from "./form/apply";

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
                <Route strict path={`${path}/:id`} children={<h1>Review application</h1>} />
                <Route children={<Link to={`${path}/apply`}>Apply</Link>} />
            </Switch>
        );
    }
}
