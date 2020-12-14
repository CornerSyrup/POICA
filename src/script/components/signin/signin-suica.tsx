import React from "react";

interface SignInSuicaProps {
  // negative for not read, 0 for reading, positive for success sign in
  state: number;
  signIn(): void;
}

interface SignInSuicaState {}

export default class SignInSuica extends React.Component<
  SignInSuicaProps,
  SignInSuicaState
> {
  constructor(props: SignInSuicaProps) {
    super(props);
  }

  renderSuicaMessage() {
    if (this.props.state < 0) {
      return <button onClick={this.props.signIn}>Sign In with Suica</button>;
    } else if (this.props.state > 0) {
      return <button disabled>Signing in with suica ...</button>;
    } else {
      return <button disabled>Signed In with Suica</button>;
    }
  }

  render() {
    return this.renderSuicaMessage();
  }
}
