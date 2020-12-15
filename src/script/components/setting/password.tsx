import React, { Component } from "react";

interface PassProps {}

interface PassState {}

export default class PassSetting extends Component<PassProps, PassState> {
  constructor(props: PassProps) {
    super(props);
  }

  render() {
    return (
      <section>
        <h3>Password Setting</h3>
        <label htmlFor="old">
          Old password
          <input type="password" />
        </label>
        <label htmlFor="new">
          new password
          <input type="password" autoComplete="new-password" />
        </label>
        <button>Update</button>
      </section>
    );
  }
}
