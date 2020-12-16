import React from "react";
import ReactDOM from "react-dom";

import SignInForm from "./components/signin/signin-form";
import SuicaButton from "./components/suica-button";
import { SignInRespond } from "./model/Respond";

import "../page/signin.pug";
import "../style/signin.less";

interface StatusMessage {
  message: string;
  warning: boolean;
}

interface SignInProps {}

interface SignInState {
  userID: string;
  password: string;
  felicaIdm: string;
  /**
   * Status of client side sign in process. E.g. "Signing in ..."
   */
  status: StatusMessage;
  isReady: boolean;
}

class SignInPage extends React.Component<SignInProps, SignInState> {
  /**
   * Non-state object, as usb nfc card reader.
   */
  readerDevice: any;
  /**
   * Interval of card reading, in millisecond.
   */
  readInterval: number = 500;

  constructor(props: SignInProps) {
    super(props);

    this.state = {
      userID: "",
      password: "",
      felicaIdm: "",
      status: {
        message: "",
        warning: false,
      },
      isReady: true,
    };
  }

  messageUpdate = (msg: string, warn: boolean) => {
    this.setState({
      status: {
        message: msg,
        warning: warn,
      },
    });
  };

  formUpdate = (user: string | null, pass: string | null) => {
    this.setState({
      userID: user ?? this.state.userID ?? "",
      password: pass ?? this.state.password ?? "",
    });
  };

  idmUpdate = (idmCode: string) => {
    this.setState(
      { status: { message: "Signing in ...", warning: false }, isReady: false },
      () => {
        this.setState({
          felicaIdm: idmCode,
        });
      }
    );

    if (!this.state.userID) {
      this.setState({
        status: {
          message: "Please provide user id",
          warning: true,
        },
      });

      return;
    }

    fetch("/signin/suica/", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        sid: this.state.userID,
        idm: this.state.felicaIdm,
      }),
    })
      .then((respond) => respond.json())
      .then((result: SignInRespond) => {
        this.setState({
          isReady: true,
        });

        if (result.status) {
          window.location.href = "/";
          this.setState({
            felicaIdm: "",
          });
        } else {
          if (result.error) {
            this.setState({
              status: {
                message: result.error?.message as string,
                warning: true,
              },
            });
          } else {
            this.setState({
              status: { message: "Suica card code mismatch", warning: false },
            });
          }
        }
      });
  };

  suicaError = (code: number) => {
    if (code < 0) {
      this.setState({
        status: {
          message: "Cannot connect to reader",
          warning: true,
        },
      });
    } else if (code > 0) {
      this.setState({
        status: {
          message: "Cannot close connection of reader",
          warning: true,
        },
      });
    } else {
      this.setState({
        status: {
          message: "Error on Suica card reading.",
          warning: true,
        },
      });
    }
  };

  formSignIn = () => {
    this.setState({
      status: { message: "Signing in ...", warning: false },
    });

    fetch("/signin/", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        sid: this.state.userID,
        pwd: this.state.password,
      }),
    })
      .then((respond) => {
        this.setState({
          password: "",
        });
        return respond.json();
      })
      .then((result: SignInRespond) => {
        if (result.status) {
          window.location.href = "/";
          this.setState({
            password: "",
          });
        } else {
          if (result.error) {
            this.setState({
              status: {
                message: result.error?.message as string,
                warning: true,
              },
            });
          } else {
            this.setState({
              status: { message: "Password incorrect", warning: false },
            });
          }
        }
      })
      .catch((reason) => {
        // TODO: store error to innoDB and sync to server later
        console.error(reason);
      });
  };

  render() {
    return (
      <div>
        <SignInForm
          user={this.state.userID}
          pass={this.state.password}
          formDataUpdate={this.formUpdate}
          formConfirm={this.formSignIn}
          updateMessage={this.messageUpdate}
        />
        {this.state.status.message && (
          <p
            className={this.state.status.warning ? "warning" : ""}
            dangerouslySetInnerHTML={{ __html: this.state.status.message }}
          ></p>
        )}
        <hr />
        <SuicaButton
          updateIdm={this.idmUpdate}
          errorHandler={this.suicaError}
          updateMessage={this.messageUpdate}
          active={this.state.isReady}
        />
        <br />
        <button disabled>Sign In with Face Recognition</button>
      </div>
    );
  }
}

ReactDOM.render(<SignInPage />, document.querySelector("body>main"));
