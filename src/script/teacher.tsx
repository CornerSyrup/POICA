import React from "react";
import ReactDOM from "react-dom";
import { Switch, Route, Redirect, NavLink as Link } from "react-router-dom";

import "../style/insider.less";
import "../page/teacher.pug";

import Insider from "./insider";
import Attend from "./page/teacher/attend_class";

class SideBar extends React.Component {
    render() {
        return (
            <React.Fragment>
                <Link to="/home" className="item" activeClassName="active">
                    <i className="home icon"></i>
                    ホーム
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
                <Route path="/home">
                    <h1>ホーム</h1>
                </Route>
                <Route strict path="/attend/:class" component={Attend} />
                <Redirect to="/home" />
            </Switch>
        );
    }
}

ReactDOM.render(
    <Insider side={SideBar} main={MainPart} />,
    document.querySelector("body>main")
);
