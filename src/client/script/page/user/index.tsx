import React from "react";
import { RouteComponentProps } from "react-router-dom";

import Suica from "./suica";

interface Props extends RouteComponentProps {}
interface State {}

export default class User extends React.Component<Props, State> {
    render() {
        return (
            <div className="eight wide centered column">
                <Suica />
            </div>
        );
    }
}
