import React from "react";
import ReactDOM from "react-dom";
import {
    BrowserRouter as Router,
    Switch,
    Route,
    Redirect,
    NavLink as Link,
} from "react-router-dom";

import "../style/insider.less";
import "../page/insider.pug";

import Form from "./page/insider/form";

class SideBar extends React.Component {
    render() {
        return (
            <React.Fragment>
                <Link to="/home" className="item" activeClassName="active">
                    <i className="home icon"></i>
                    home
                </Link>
                <Link to="/form" className="item" activeClassName="active">
                    <i className="tasks icon"></i>
                    form
                </Link>
                <Link to="/attend" className="item" activeClassName="active">
                    <i className="calendar alternate icon"></i>
                    attendance
                </Link>
                <Link to="/user" className="item" activeClassName="active">
                    <i className="user icon"></i>
                    account
                </Link>
                <Link to="/setting" className="item" activeClassName="active">
                    <i className="sliders horizontal icon"></i>
                    setting
                </Link>
                <Link to="/about" className="item" activeClassName="active">
                    <i className="info icon"></i>
                    about
                </Link>
            </React.Fragment>
        );
    }
}

class MainPart extends React.Component {
    render() {
        return (
            <Switch>
                <Route path="/form" component={Form} />
                <Route path="/home">
                    <h1>Home</h1>
                </Route>
                <Redirect to="/home" />
            </Switch>
        );
    }
}

class Insider extends React.Component {
    render() {
        return (
            <Router>
                <aside
                    id="sidebar"
                    className="ui three wide column secondary vertical pointing menu"
                >
                    <SideBar />
                </aside>
                <section
                    id="core"
                    className="ui thirteen wide column padded grid"
                >
                    <MainPart />
                </section>
            </Router>
        );
    }
}

ReactDOM.render(<Insider />, document.querySelector("body>main"));
