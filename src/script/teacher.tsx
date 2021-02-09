import React from "react";
import ReactDOM from "react-dom";
import { Switch, Route, Redirect, NavLink as Link } from "react-router-dom";

import "../style/insider.less";
import "../page/teacher.pug";

import Insider, { SideBarItem as Item } from "./insider";
import Attend from "./page/teacher/attend_class";

class SideBar extends React.Component {
    render() {
        return (
            <React.Fragment>
                <Item route="/home" icon="home" tag="ホーム" />
                <Item route="/attend" icon="calendar alternate" tag="出席" />
                {/* <Item route="/user" icon="user" tag="アカウント" /> */}
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
