import React from "react";
import { BrowserRouter as Router } from "react-router-dom";

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
                {props.side}
            </aside>
            <section
                id="core"
                className="ui thirteen wide padded grid stretched column"
            >
                {props.main}
            </section>
        </Router>
    );
}
