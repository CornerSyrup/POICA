import React, { Component } from "react";

import Reader from "../suica-button";
import { SuicaRegisterRespond as Respond } from "../../model/Respond";

interface SuicaProps {}

interface SuicaState {
  /**
   * Ready to read felica card
   */
  canRead: boolean;
  status: {
    message: string;
    warning: boolean;
  };
}

export default class SuicaSetting extends Component<SuicaProps, SuicaState> {
  constructor(props: SuicaProps) {
    super(props);

    this.state = {
      canRead: true,
      status: {
        message: "",
        warning: false,
      },
    };
  }

  handleReadError(code: number, msg: string) {}

  handleMessage = (msg: string, warn: boolean) => {
    this.setState({
      status: {
        message: msg,
        warning: warn,
      },
    });
  };

  readIdm = (code: string) => {
    this.setState({
      canRead: false,
      status: {
        message: "Updating your Suica card ...",
        warning: false,
      },
    });

    fetch("/regis/suica/", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        idm: code,
      }),
    })
      .then(
        (respond) => respond.json(),
        (reason) => console.error(reason.text())
      )
      .then((result: Respond) => {
        console.log(result);
        this.setState({
          canRead: true,
        });
        
        if (result.status) {
          this.setState({
            status: {
              message: "Update succeed",
              warning: false,
            },
          });
        } else {
          this.setState({
            status: {
              message: result.error?.message as string,
              warning: true,
            },
          });
          console.error(result);
        }
      })
      .catch((reason) => {
        console.error(reason);
        this.setState({
          status: {
            message: "Update succeed",
            warning: false,
          },
        });
      });
  };

  render() {
    return (
      <section>
        <h3>Suica Setting</h3>
        <Reader
          errorHandler={this.handleReadError}
          updateIdm={this.readIdm}
          updateMessage={this.handleMessage}
          active={this.state.canRead}
        />
        {this.state.status.message && (
          <p className={this.state.status.warning ? "warning" : ""}>
            {this.state.status.message}
          </p>
        )}
      </section>
    );
  }
}
