import React from "react";
import ReactDOM from "react-dom";
import { Switch, Route, Redirect } from "react-router-dom";

import "../style/insider.less";
import "../page/student.pug";

import Insider, { SideBarItem as Item } from "./insider";
import Form from "./page/form";
import User from "./page/user";

class SideBar extends React.Component {
    render() {
        return (
            <React.Fragment>
                <Item route="/home" icon="home" tag="ホーム" />
                <Item route="/form" icon="tasks" tag="申し込み" />
                <Item route="/attend" icon="calendar alternate" tag="出席" />
                <Item route="/user" icon="user" tag="アカウント" />
                {/* <Item route="/setting" icon="sliders horizontal" tag="設定" /> */}
                {/* <Item route="/about" icon="info" tag="情報" /> */}
            </React.Fragment>
        );
    }
}

class MainPart extends React.Component {
    render() {
        return (
            <Switch>
                <Route path="/form" component={Form} />
                <Route path="/user" component={User} />
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
