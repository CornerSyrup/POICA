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
                        <Route path="/signin/">
                            <SignIn />
                        </Route>
                        <Route path="/signup/">
                            <SignUp />
                        </Route>
                        <Route path="/">
                            <Redirect to="/signin/" />
                        </Route>
                    </Switch>
                </Router>
            </div>
        );
    }
}

ReactDOM.render(<SignPage />, document.querySelector("body>main"));
