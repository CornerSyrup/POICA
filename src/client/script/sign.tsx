import React from "react";
import ReactDOM from "react-dom";
import {
    BrowserRouter as Router,
    Switch,
    Route,
    Redirect,
} from "react-router-dom";

import "../style/sign.less";
import "../page/sign.pug";

import SignIn from "./page/sign/signin";
import SignUp from "./page/sign/signup";

class SignPage extends React.Component {
    render() {
        return (
            <div className="right floated middle aligned six wide column">
                <Router>
                    <Switch>
                        <Route path="/signin" component={SignIn} />
                        <Route path="/signup" component={SignUp} />
                        <Redirect to="/signin" />
                    </Switch>
                </Router>
            </div>
        );
    }
}

ReactDOM.render(<SignPage />, document.querySelector("body>main"));
