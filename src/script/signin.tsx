import React, { ChangeEvent } from "react";
import ReactDOM from "react-dom";

import "../page/signin.pug";
import "../style/signin.less";

interface SignInProps {}

interface SignInState {
  userID: string;
  password: string;
}

class SignIn extends React.Component<SignInProps, SignInState> {
  constructor(props: ChangeEvent<HTMLInputElement>) {
    super(props);

    this.state = {
      userID: "",
      password: "",
    };
  }

  UserIdUpdate = (ev: ChangeEvent<HTMLInputElement>) => {
    this.setState({
      userID: ev.target.value ?? "",
    });
  };

  PassUpdate = (ev: any) => {
    this.setState({
      password: ev.target.value ?? "",
    });
  };

  render() {
    return (
      <div className="signin-form">
        <label htmlFor="sid">Student ID:</label>
        <input
          type="text"
          value={this.state.userID}
          onChange={this.UserIdUpdate}
        />
        <label htmlFor="pwd">Password:</label>
        <input
          type="password"
          value={this.state.password}
          onChange={this.PassUpdate}
        />
        <input type="submit" value="Sign In" />
      </div>
    );
  }
}

ReactDOM.render(<SignIn />, document.querySelector("body>main"));
