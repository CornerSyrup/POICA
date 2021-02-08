import React from "react";
import ReactDOM from "react-dom";
import { Switch, Route, Redirect, NavLink as Link } from "react-router-dom";

import "../style/insider.less";
import "../page/student.pug";

import Insider from "./insider";
import Form from "./page/student/form";

class SideBar extends React.Component {
    render() {
        return (
            <React.Fragment>
                <Link to="/home" className="item" activeClassName="active">
                    <i className="home icon"></i>
                    ホーム
                </Link>
                <Link to="/form" className="item" activeClassName="active">
                    <i className="tasks icon"></i>
                    フォーム
                </Link>
                <Link to="/attend" className="item" activeClassName="active">
                    <i className="calendar alternate icon"></i>
                    出席
                </Link>
                <Link to="/user" className="item" activeClassName="active">
                    <i className="user icon"></i>
                    アカウント
                </Link>
                <Link to="/setting" className="item" activeClassName="active">
                    <i className="sliders horizontal icon"></i>
                    設定
                </Link>
                <Link to="/about" className="item" activeClassName="active">
                    <i className="info icon"></i>
                    情報
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

ReactDOM.render(
    <Insider side={SideBar} main={MainPart} />,
    document.querySelector("body>main")
);
