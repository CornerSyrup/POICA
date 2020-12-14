import React, { ChangeEvent, KeyboardEvent } from "react";

import { StatusMessage } from "../../signin";

export interface SignInFormData {
  user?: string;
  pass?: string;
}

interface SignInFormProps {
  user: string;
  pass: string;
  formDataUpdate(state: SignInFormData): void;
  formConfirm(): void;
  updateMessage(msg: StatusMessage): void;
}

interface SignInFormState {
  /**
   * Using form to sign in, false for uncertain.
   */
  formSignIn: boolean;
}

export default class SignInForm extends React.Component<
  SignInFormProps,
  SignInFormState
> {
  constructor(props: SignInFormProps) {
    super(props);
    this.state = {
      formSignIn: false,
    };
  }

  userUpdate = (ev: ChangeEvent<HTMLInputElement>) => {
    this.props.formDataUpdate({
      user: ev.target.value,
    });
    this.setState({
      formSignIn: false,
    });
  };

  passUpdate = (ev: ChangeEvent<HTMLInputElement>) => {
    this.props.formDataUpdate({
      pass: ev.target.value,
    });
  };

  userIdShortCut = (ev: KeyboardEvent<HTMLElement>) => {
    switch (ev.key) {
      case "Enter":
        this.continueForm();
        break;
    }
  };

  passShortCut = (ev: KeyboardEvent<HTMLElement>) => {
    switch (ev.key) {
      case "Enter":
        this.props.formConfirm();
        break;
    }
  };

  // fix: update to fit
  continueForm = () => {
    // validate user id
    if (/^\d{5}$/.test(this.props.user)) {
      this.setState({
        formSignIn: true,
      });
    }
    // user id format invalid
    else {
      this.props.updateMessage({
        message: "User ID incorrect",
        warning: true,
      });
    }
  };

  render() {
    return (
      <div className="signin-form">
        <label>
          Student ID:
          <input
            type="text"
            value={this.props.user}
            onChange={this.userUpdate}
            onKeyDown={this.userIdShortCut}
          />
        </label>
        {this.state.formSignIn && (
          <label>
            Password:
            <input
              type="password"
              value={this.props.pass}
              onChange={this.passUpdate}
              onKeyDown={this.passShortCut}
            />
          </label>
        )}
        <button
          onClick={
            this.state.formSignIn ? this.props.formConfirm : this.continueForm
          }
        >
          {this.state.formSignIn ? "Sign In" : "Continue"}
        </button>
      </div>
    );
  }
}