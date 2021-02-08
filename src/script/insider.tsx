import React from "react";
import { BrowserRouter as Router, NavLink as Link } from "react-router-dom";

export default function Insider(props: {
    side: React.ComponentType<any>;
    main: React.ComponentType<any>;
}) {
    return (
        <Router>
            <aside
                id="sidebar"
                className="ui three wide secondary vertical pointing menu column "
            >
                <props.side />
            </aside>
            <section
                id="core"
                className="ui thirteen wide padded grid stretched column"
            >
                <props.main />
            </section>
        </Router>
    );
}

export function SideBarItem(props: {
    route: string;
    icon: string;
    tag: string;
}) {
    return (
        <Link to={props.route} className="item" activeClassName="active">
            <i className={`${props.icon} icon`}></i>
            {props.tag}
        </Link>
    );
}
